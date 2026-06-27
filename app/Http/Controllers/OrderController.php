<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateOrderRequest;
use App\Jobs\ProcessOrdersChunkJob;
use Illuminate\Bus\Batch;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Requests\UpdateOrderRequest;
use App\Jobs\CreateOrderJob;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Status;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;



class OrderController extends Controller
{

    public function store(CreateOrderRequest $request, string $mode)
    {
        $requestId = (string) Str::uuid();
        if ($mode === 'before') {
            $start = microtime(true);
            $this->createUnsafeOrder($request);
            $time = round((microtime(true) - $start) * 1000, 2);
            return response()->json([
                'mode' => 'before',
                'request_id' => $requestId,
                'time_ms' => $time
            ]);
        }
        $data = $request->validated();
        CreateOrderJob::dispatch(
            $data,
            auth()->id(),
            $requestId
        );
        return response()->json([
            'status' => 'queued',
            'server' => $_SERVER['SERVER_PORT'],   // ← وهون كمان
            'request_id' => $requestId
        ]);
    }

    private function createUnsafeOrder($request)
    {
        $pending = Status::where('status_en', 'pending')->firstOrFail();
        $totalPrice = 0;
        $countItems = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {continue;}
            $quantity = $item['quantity'];
            $totalPrice += $product->price * $quantity;
            $countItems += $quantity;}
        $order = Order::create(['user_id' => auth()->id(), 'store_id' => $request->store_id, 'address_id' => $request->address_id, 'payment_method' => $request->payment_method, 'status_id' => $pending->id, 'total_price' => $totalPrice, 'code' => 'ORD-' . uniqid(), 'date' => now(), 'count_items' => $countItems,
        ]);
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {continue;}
            $product->stock -= $item['quantity'];
            $product->save();
            usleep(200000);}
        return $order;}

    public function compare(CreateOrderRequest $request)
    {
        $data = $request->validated();


        $startBefore = microtime(true);

        $this->createUnsafeOrder($request);

        $beforeTime = (microtime(true) - $startBefore) * 1000;
        $startAfter = microtime(true);

        CreateOrderJob::dispatch(
            $data,
            auth()->id(),
            (string) Str::uuid()
        );

        $afterTime = (microtime(true) - $startAfter) * 1000;

        return response()->json([
            'BEFORE_SYNC_MS' => round($beforeTime, 2),
            'AFTER_DISPATCH_MS' => round($afterTime, 4),
            'IMPROVEMENT' => round($beforeTime - $afterTime, 2)
        ]);
    }


    public function batchProcess()
    {
        $orderIds = Order::where('processed', false)->pluck('id')->toArray();
        if (empty($orderIds)) {
            return response()->json(['status' => 'nothing_to_process']);}
        $chunks = array_chunk($orderIds, 25);
        $jobs = [];
        foreach ($chunks as $chunk) {
            $jobs[] = new ProcessOrdersChunkJob($chunk);}
        $start = microtime(true);
        $batch = Bus::batch($jobs)->name('orders-batch')->then(function ($batch) use ($start) {
                $execution = round((microtime(true) - $start) * 1000, 2);
                cache()->put("batch_time_{$batch->id}", $execution, 3600);
            })
            ->dispatch();

        return response()->json([
            'mode' => 'batch',
            'batch_id' => $batch->id
        ]);
    }

    public function batchStatus($batchId)
    {
        $batch = Bus::findBatch($batchId);

        if (!$batch) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'progress' => $batch->progress(),
            'finished' => $batch->finished(),
            'processed_jobs' => $batch->processedJobs(),
            'total_jobs' => $batch->totalJobs,
            'execution_time_ms' => cache("batch_time_{$batchId}")
        ]);
    }

    public function processWithoutBatch()
    {
        $start = microtime(true);

        $orders = Order::where('processed', false)->get();

        if ($orders->isEmpty()) {
            return response()->json(['status' => 'nothing_to_process']);
        }

        $totalSales = 0;

        foreach ($orders as $order) {

            $t = microtime(true);

            $hash = $order->id;

            for ($i = 0; $i < 100; $i++) {
                $hash = hash('sha256', $hash . $i);
            }

            for ($i = 0; $i < 100; $i++) {
                DB::table('fake_logs')->insert([
                    'order_id' => $order->id,
                    'payload' => str_repeat('x', 2000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $order->processed = true;
            $order->processed_at = now();
            $order->save();

            $totalSales += $order->total_price;

            Log::info("SYNC_ORDER_TIME", [
                'order_id' => $order->id,
                'time_ms' => round((microtime(true) - $t) * 1000, 2)
            ]);
        }

        DB::table('daily_reports')->insert([
            'total_sales' => $totalSales,
            'orders_count' => $orders->count(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $total = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'mode' => 'sync',
            'orders' => $orders->count(),
            'sales' => $totalSales,
            'execution_time_ms' => $total
        ]);
    }


    public function update(UpdateOrderRequest $request, Order $order)
    {
        if ($order->status->status_en !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending orders can be updated.'], 403);
        }

        $order->update($request->validated());

        return response()->json(['success' => true, 'message' => 'Order updated successfully.', 'data' => $order]);
    }

    public function destroy(Order $order)
    {
        if ($order->status->status_en !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending orders can be deleted.'], 403);
        }

        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
    }

    public function index(Request $request)
    {
        $orders = Order::with(['items.product:id,name,price', 'status:id,status_en'])
            ->where('user_id', auth()->id())
            ->when($request->has('status'), fn($q) =>
            $q->whereHas('status', fn($s) =>
            $s->where('status_en', $request->status)
            )
            )
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function vendorOrders(Request $request)
    {
        $store = Store::where('user_id', auth()->id())->firstOrFail();

        $orders = Order::with(['items.product:id,name,price', 'status:id,status_en', 'user:id,name,email'])
            ->where('store_id', $store->id)
            ->when($request->has('status'), fn($q) =>
            $q->whereHas('status', fn($s) =>
            $s->where('status_en', $request->status)
            )
            )
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function approve(Order $order)
    {
        $store = Store::where('user_id', auth()->id())->firstOrFail();

        if ($order->store_id !== $store->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($order->status->status_en !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending orders can be approved.'], 403);
        }

        $approvedStatus = Status::where('status_en', 'approved')->firstOrFail();

        $order->update(['status_id' => $approvedStatus->id]);

        return response()->json(['success' => true, 'message' => 'Order approved successfully.']);
    }
}


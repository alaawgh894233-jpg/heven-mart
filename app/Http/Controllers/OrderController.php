<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Status;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(CreateOrderRequest $request)
    {
        $validated = $request->validated();

        $productIds = collect($validated['items'])->pluck('product_id');
        $storeProducts = Product::whereIn('id', $productIds)
            ->where('store_id', $validated['store_id'])
            ->pluck('id')
            ->toArray();

        foreach ($productIds as $productId) {
            if (!in_array($productId, $storeProducts)) {
                return response()->json(['success' => false, 'message' => 'Some products do not belong to this store.'], 422);
            }
        }

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'store_id' => $validated['store_id'],
                'status_id' => Status::where('status_en', 'pending')->value('id'),
                'address_id' => $validated['address_id'],
                'payment_method' => $validated['payment_method'],
                'total_price' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            $order->update(['total_price' => $total]);

            return $order;
        });

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => $order->load('items.product')
        ], 201);
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


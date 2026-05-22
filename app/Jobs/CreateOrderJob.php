<?php

namespace App\Jobs;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;
    public int $userId;
    public string $requestId;

    public function __construct($data, $userId, $requestId)
    {
        $this->data = $data;
        $this->userId = $userId;
        $this->requestId = $requestId;
    }

    public function handle()
    {
        DB::transaction(function () {

            $pending = Status::where('status_en', 'pending')
                ->firstOrFail();

            $order = Order::create([
                'user_id' => $this->userId,
                'store_id' => $this->data['store_id'],
                'address_id' => $this->data['address_id'],
                'payment_method' => $this->data['payment_method'],
                'status_id' => $pending->id,
                'total_price' => 0,
                'code' => 'ORD-' . uniqid(),
                'date' => now(),
                'count_items' => 0,
            ]);

            $total = 0;
            $count = 0;

            foreach ($this->data['items'] as $item) {

                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception('OUT OF STOCK');
                }

                $product->decrement('stock', $item['quantity']);

                $total += $product->price * $item['quantity'];
                $count += $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'status_id' => $pending->id,
                ]);
            }

            $order->update([
                'total_price' => $total,
                'count_items' => $count
            ]);
        });
    }
}

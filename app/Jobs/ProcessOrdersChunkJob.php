<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOrdersChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public array $orderIds;

    public function __construct(array $orderIds)
    {
        $this->orderIds = $orderIds;
    }

    public function handle()
    {
        $start = microtime(true);
        Log::info("CHUNK_START", [
            'count' => count($this->orderIds)
        ]);
        $orders = Order::whereIn('id', $this->orderIds)->get();
        if ($orders->isEmpty()) return;
        $total = $orders->sum('total_price');
        Order::whereIn('id', $this->orderIds)->update([
            'processed' => true,
            'processed_at' => now()
        ]);
        DB::table('daily_reports')->insert([
            'total_sales' => $total,
            'orders_count' => $orders->count(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $time = round((microtime(true) - $start) * 1000, 2);

        Log::info("CHUNK_DONE", [
            'count' => count($this->orderIds),
            'time_ms' => $time
        ]);
    }
}

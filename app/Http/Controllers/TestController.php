<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    // ====== TOP PRODUCTS ======

    public function topProductsNoCache()
    {
        $start = microtime(true);

        $products = Product::selectRaw('*, (num_of_purchase * rate) as score')
            ->orderByDesc('score')
            ->take(10)
            ->get();

        $time = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'mode' => 'no-cache',
            'time_ms' => $time,
            'data' => $products,
        ]);
    }

    public function topProductsCache()
    {
        $start = microtime(true);

        $products = Cache::remember('top_products_NEW', 600, function () {
            return Product::selectRaw('*, (num_of_purchase * rate) as score')
                ->orderByDesc('score')
                ->take(10)
                ->get();
        });

        $time = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'mode' => 'cache',
            'time_ms' => $time,  // ← حذفت cache_status
            'data' => $products,
        ]);
    }

    // ====== DAILY REPORT ======

    public function dailyReportNoCache()
    {
        $start = microtime(true);

        $report = DB::table('daily_reports')
            ->selectRaw('COUNT(*) as orders_count, SUM(total_sales) as total_sales')
            ->first();

        $time = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'mode' => 'no-cache',
            'time_ms' => $time,
            'data' => $report,
        ]);
    }

    public function dailyReportCache()
    {
        $start = microtime(true);

        $report = Cache::remember('daily_report', 600, function () {
            return DB::table('daily_reports')
                ->selectRaw('COUNT(*) as orders_count, SUM(total_sales) as total_sales')
                ->first();
        });

        $time = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'mode' => 'cache',
            'time_ms' => $time,
            'data' => $report,
        ]);
    }

    // ====== PRODUCT DETAILS ======

    public function productNoCache($id)
    {
        $start = microtime(true);

        $product = Product::findOrFail($id);

        $time = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'mode' => 'no-cache',
            'time_ms' => $time,
            'data' => $product,
        ]);
    }

    public function productCache($id)
    {
        $start = microtime(true);

        $product = Cache::remember("product_$id", 600, function () use ($id) {
            return Product::findOrFail($id);
        });

        $time = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'mode' => 'cache',
            'time_ms' => $time,
            'data' => $product,
        ]);
    }

    public function clearCache()
    {
        Cache::flush();

        return response()->json([
            'status' => 'cache cleared',
        ]);
    }
}

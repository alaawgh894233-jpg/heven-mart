<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function topProductsNoCache()
    {
        sleep(1);

        $products = Product::selectRaw('*, (num_of_purchase * rate) as score')
            ->orderByDesc('score')
            ->take(1000)
            ->get();

        return response()->json($products);
    }

    public function dailyReportNoCache()
    {
        sleep(1);

        $report = DB::table('daily_reports')
            ->selectRaw('COUNT(*) as orders_count, SUM(total_sales) as total_sales')
            ->first();

        return response()->json($report);
    }

    public function productNoCache($id)
    {
        sleep(1);

        $product = Product::findOrFail($id);

        return response()->json($product);
    }


    public function topProductsCache()
    {


        $products = Cache::remember('top_products_NEW', 600, function () {

            return Product::selectRaw('*, (num_of_purchase * rate) as score')
                ->orderByDesc('score')
                ->take(10)
                ->get();
        });

        return response()->json($products);
    }

    public function dailyReportCache()
    {
        $report = Cache::remember('daily_report', 600, function () {


            return DB::table('daily_reports')
                ->selectRaw('COUNT(*) as orders_count, SUM(total_sales) as total_sales')
                ->first();
        });

        return response()->json($report);
    }

    public function productCache($id)
    {
        $product = Cache::remember("product_$id", 600, function () use ($id) {



            return Product::findOrFail($id);
        });

        return response()->json($product);
    }


    public function clearCache()
    {
        Cache::flush();

        return response()->json([
            'status' => 'cache cleared'
        ]);
    }
}

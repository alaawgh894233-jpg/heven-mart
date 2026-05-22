<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function add(Cart $cart, Product $product, int $qty): bool
    {
        if ($qty === 0) return false;

        return DB::transaction(function () use ($cart, $product, $qty) {

            $cart = Cart::where('id', $cart->id)->lockForUpdate()->first();
            $product = Product::where('id', $product->id)->lockForUpdate()->first();


            if ($qty > 0) {

                if ($product->stock < $qty) {
                    throw new \Exception("OUT_OF_STOCK");
                }

                $item = $cart->items()
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if ($item) {
                    $item->quantity += $qty;
                    $item->save();
                } else {
                    $cart->items()->create([
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'price'      => $product->price,
                    ]);
                }

                $product->stock -= $qty;
                $product->save();
            }

            if ($qty < 0) {

                $item = $cart->items()
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if (!$item) return false;

                $removeQty = min(abs($qty), $item->quantity);

                $item->quantity -= $removeQty;

                if ($item->quantity <= 0) {
                    $item->delete();
                } else {
                    $item->save();
                }

                $product->stock += $removeQty;
                $product->save();
            }

            $cart->recalc();

            return true;
        });
    }

    public function delete(Cart $cart, Product $product): bool
    {
        return DB::transaction(function () use ($cart, $product) {

            $cart = Cart::where('id', $cart->id)->lockForUpdate()->first();
            $product = Product::where('id', $product->id)->lockForUpdate()->first();

            $item = $cart->items()->where('product_id', $product->id)->first();

            if (!$item) return false;

            $product->stock += $item->quantity;
            $product->save();

            $item->delete();

            $cart->recalc();

            return true;
        });
    }

    public function clear(Cart $cart): bool
    {
        return DB::transaction(function () use ($cart) {

            $cart = Cart::where('id', $cart->id)->lockForUpdate()->first();

            foreach ($cart->items as $item) {
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                $product->stock += $item->quantity;
                $product->save();
            }

            $cart->items()->delete();

            $cart->update([
                'total_price' => 0,
                'quantity' => 0,
            ]);

            return true;
        });
    }
}

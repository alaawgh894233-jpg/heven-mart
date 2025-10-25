<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class CartService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public function get(Cart $cart, $lang)
    {
        $items = $cart->items()->with('product.primaryImage')->get();

        $products = $items->map(function ($item) use ($lang) {
            return [
                'id' => $item->product->id,
                'name' => $item->product['name_'. $lang],
                'description' => $item->product['description_'. $lang],
                'price' => $item->product->price,
                'image' => $item->product->primaryImage->url_image ?? null,
                'quantity' => $item->quantity,
            ];
        });

        return [
            'total_price' => $cart->total_price,
            'count_items' => $cart->quantity,
            'products' => $products,
        ];
    }

    public function add(Cart $cart, Product $product, int $quantity): bool
    {
        if ($quantity < 1 || $quantity > $product->stock) {
            return false;
        }

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $newQty = $item->quantity + $quantity;

            if ($newQty > $product->stock) {
                return false;
            }

            $item->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity,
//                'price'      => $product->price,
            ]);
        }

        $this->updateCartTotals($cart);
        return true;
    }

    public function decrement(Cart $cart, Product $product): bool
    {
        $item = $cart->items()->where('product_id', $product->id)->first();

        if (!$item) {
            return false;
        }

        if ($item->quantity <= 1) {
            $item->delete();
        } else {
            $item->decrement('quantity');
        }

        $this->updateCartTotals($cart);
        return true;
    }

    public function delete(Cart $cart, Product $product): bool
    {
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->delete();
            $this->updateCartTotals($cart);
            return true;
        }

        return false;
    }

    public function clear(Cart $cart): bool
    {
        $cart->items()->delete();

        $cart->update([
            'total_price' => 0,
            'quantity' => 0,
        ]);

        return true;
    }


    private function updateCartTotals(Cart $cart)
    {
        $totalPrice = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $quantity = $cart->items->sum('quantity');

        $cart->update([
            'total_price' => $totalPrice,
            'quantity'    => $quantity,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request, int $productId)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);

        $product = Product::findOrFail($productId);

        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            ['total_price' => 0, 'quantity' => 0]
        );

        try {
            $ok = $this->cartService->add(
                $cart,
                $product,
                $request->quantity ?? 1
            );

            return response()->json(['success' => $ok]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function plusOne(int $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = auth()->user()->cart;

        $result = $this->cartService->add($cart, $product, 1);

        return response()->json([
            'message' => $result ? 'Quantity increased' : 'Failed',
        ]);
    }

    public function minusOne(int $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = auth()->user()->cart;

        $result = $this->cartService->add($cart, $product, -1);

        return response()->json([
            'message' => $result ? 'Quantity decreased' : 'Failed',
        ]);
    }

    public function deleteFromCart(int $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = auth()->user()->cart;

        $result = $this->cartService->delete($cart, $product);

        return response()->json([
            'message' => $result ? 'Deleted successfully' : 'Failed',
        ]);
    }

    public function clearCart()
    {
        $cart = auth()->user()->cart;

        $result = $this->cartService->clear($cart);

        return response()->json([
            'message' => $result ? 'Cart cleared' : 'Failed',
        ]);
    }public function getCart(Request $request)
{
    $cart = auth()->user()->cart;

    if (!$cart) {
        return response()->json([
            'cart' => null
        ]);
    }

    $cart->load('items.product'); // ✅ مهم جداً

    return response()->json([
        'cart' => [
            'id' => $cart->id,
            'total_quantity' => $cart->quantity,
            'total_price' => $cart->total_price,

            'items' => $cart->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? null,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->price * $item->quantity,
                ];
            })
        ]
    ]);
}
}

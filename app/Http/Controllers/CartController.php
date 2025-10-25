<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Exception;



class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }


    public function getCart(Request $request)
    {
        $lang = $request->header('lang', 'en');
        $cart = auth()->user()->cart;

        if (!$cart) {
            return response()->json(['cart' => null], 200);
        }

        $cartWithData = $this->cartService->get($cart, $lang);

        return response()->json(['cart' => $cartWithData], 200);
    }


    public function addToCart(Request $request, int $productId)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);
        $quantity = $request->quantity ?? 1;

        $cart = auth()->user()->cart;
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'total_price' => 0,
                'quantity' => 0,
            ]);
        }

        $result = $this->cartService->add($cart, $product, $quantity);

        return response()->json([
            'message' => $result ? 'Product added to cart successfully' : 'Product not added to cart',
        ], $result ? 200 : 400);
    }

    public function plusOne(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = auth()->user()->cart;
        $result = $this->cartService->add($cart, $product, +1);

        return response()->json([
            'message' => $result ? 'Product quantity increased' : 'Product not updated',
        ], $result ? 200 : 400);
    }

    public function minusOne(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = auth()->user()->cart;
        $result = $this->cartService->add($cart, $product,-1);

        return response()->json([
            'message' => $result ? 'Product quantity decreased' : 'Product not updated',
        ], $result ? 200 : 400);
    }

    public function deleteFromCart(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = auth()->user()->cart;
        $result = $this->cartService->delete($cart, $product);

        return response()->json([
            'message' => $result ? 'Product deleted successfully' : 'Product not deleted',
        ], $result ? 200 : 400);
    }

    public function clearCart(Request $request)
    {
        $cart = auth()->user()->cart;
        $result = $this->cartService->clear($cart);

        return response()->json([
            'message' => $result ? 'Cart cleared successfully' : 'Failed to clear cart',
        ], $result ? 200 : 400);
    }
//}

//    public function checkout(Request $request)
//    {
//        DB::beginTransaction();
//
//        try {
//            $user = auth()->user();
//            $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
//
//            if (!$cart || $cart->items->isEmpty()) {
//                return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
//            }
//
//            $order = Order::create([
//                'user_id'     => $user->id,
//                'store_id'    => $cart->store_id ?? null,
//                'total_price' => $cart->total_price,
//                'status'      => 'pending',
//            ]);
//
//            foreach ($cart->items as $item) {
//                $product = $item->product;
//
//                if ($product->quantity < $item->quantity) {
//                    throw new Exception("Insufficient stock for product: {$product->name}");
//                }
//
//                $order->items()->create([
//                    'product_id' => $item->product_id,
//                    'quantity'   => $item->quantity,
//                    'price'      => $item->price,
//                ]);
//
//                $product->decrement('quantity', $item->quantity);
//            }
//
//            $cart->items()->delete();
//            $cart->update(['total_price' => 0]);
//
//            DB::commit();
//
//            $order->load('items.product');
//
//            return response()->json([
//                'success' => true,
//                'message' => 'Order placed successfully.',
//                'data'    => $order,
//            ]);
//        } catch (Exception $e) {
//            DB::rollBack();
//
//            return response()->json([
//                'success' => false,
//                'message' => 'Checkout failed: ' . $e->getMessage(),
//            ], 500);
//        }
//    }
//}

//    public function index()
//    {
//        $carts = Cart::with(['items.product'])
//            ->where('user_id', auth()->id())
//            ->get();
//
//        return response()->json(['success' => true, 'data' => $carts]);
//    }
//
//    public function addItem(AddToCartRequest $request)
//    {
//        $validated = $request->validated();
//
//        $product = Product::findOrFail($validated['product_id']);
//
//        if ($product->store_id != $validated['store_id']) {
//            return response()->json(['success' => false, 'message' => 'Product does not belong to this store.'], 400);
//        }
//
//        if ($product->quantity < $validated['quantity']) {
//            return response()->json(['success' => false, 'message' => 'Not enough product in stock.'], 400);
//        }
//
//        $cart = Cart::firstOrCreate([
//            'user_id' => auth()->id(),
//            'store_id' => $validated['store_id'],
//        ]);
//
//        $item = $cart->items()->where('product_id', $product->id)->first();
//
//        if ($item) {
//            $newQty = $item->quantity + $validated['quantity'];
//            if ($newQty > $product->quantity) {
//                return response()->json(['success' => false, 'message' => 'Exceeds stock.'], 400);
//            }
//            $item->update(['quantity' => $newQty]);
//        } else {
//            $cart->items()->create([
//                'product_id' => $product->id,
//                'quantity'   => $validated['quantity'],
//            ]);
//        }
//
//        $cart->updateTotalPrice();
//
//        return response()->json(['success' => true, 'message' => 'Item added/updated.']);
//    }
//
//    public function updateQuantity(Request $request, CartItem $item)
//    {
//        $request->validate(['type' => 'required|in:increment,decrement']);
//        $this->authorizeItem($item);
//
//        $product = $item->product;
//
//        if ($request->type === 'increment') {
//            if ($item->quantity + 1 > $product->quantity) {
//                return response()->json(['success' => false, 'message' => 'Exceeds stock.'], 400);
//            }
//            $item->increment('quantity');
//        } else {
//            if ($item->quantity <= 1) {
//                $item->delete();
//            } else {
//                $item->decrement('quantity');
//            }
//        }
//
//        $item->cart->updateTotalPrice();
//
//        return response()->json(['success' => true, 'message' => 'Quantity updated.']);
//    }
//
//    public function removeItem(CartItem $item)
//    {
//        $this->authorizeItem($item);
//        $cart = $item->cart;
//
//        $item->delete();
//        $cart->updateTotalPrice();
//
//        return response()->json(['success' => true, 'message' => 'Item removed.']);
//    }
//
////    public function clearCart(Cart $cart)
////    {
////        if ($cart->user_id != auth()->id()) {
////            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
////        }
////
////        $cart->items()->delete();
////        $cart->update(['total_price' => 0]);
////
////        return response()->json(['success' => true, 'message' => 'Cart cleared.']);
////    }
//
//
//
//    public function checkout(Request $request)
//    {
//        DB::beginTransaction();
//
//        try {
//            $user = auth()->user();
//
//            $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
//
//            if (!$cart || $cart->items->isEmpty()) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Cart is empty.',
//                ], 400);
//            }
//
//            $order = Order::create([
//                'user_id'     => $user->id,
//                'store_id'    => $cart->store_id,
//                'total_price' => $cart->total_price,
//                'status'      => 'pending',
//            ]);
//
//            foreach ($cart->items as $item) {
//                $product = $item->product;
//
//                if ($product->quantity < $item->quantity) {
//                    throw new \Exception("Insufficient stock for product: {$product->name}");
//                }
//
//
//                $order->items()->create([
//                    'product_id' => $item->product_id,
//                    'quantity'   => $item->quantity,
//                    'price'      => $item->price,
//                ]);
//
//
//                $product->decrement('quantity', $item->quantity);
//            }
//
//            $cart->items()->delete();
//            $cart->update(['total_price' => 0]);
//
//            DB::commit();
//
//            $order->load('items.product');
//
//            return response()->json([
//                'success' => true,
//                'message' => 'Order placed successfully.',
//                'data'    => $order
//            ]);
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//
//            return response()->json([
//                'success' => false,
//                'message' => 'Checkout failed: ' . $e->getMessage(),
//            ], 500);
//        }
//    }
//
//    private function authorizeItem(CartItem $item)
//    {
//        if ($item->cart->user_id !== auth()->id()) {
//            abort(403, 'Unauthorized.');
//        }
//    }
}

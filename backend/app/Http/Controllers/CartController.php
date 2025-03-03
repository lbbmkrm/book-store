<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Menampilkan isi cart user.
     */
    public function index(): JsonResource|JsonResponse
    {
        $cart = Cart::where('user_id', Auth::id())->with('cartDetails.book')->first();
        if (!$cart || $cart->cartDetails->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        return new CartResource($cart);
    }

    /**
     * Menambahkan produk ke dalam cart.
     */
    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Fail to add product to cart',
                'errors' => $validate->errors()
            ], 422);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('book_id', $request->book_id)
            ->first();

        if ($cartDetail) {
            $cartDetail->increment('quantity', $request->quantity);
        } else {
            CartDetail::create([
                'cart_id' => $cart->id,
                'book_id' => $request->book_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['message' => 'Product added to cart'], 201);
    }

    /**
     * Menambah jumlah produk dalam cart.
     */
    public function incrementQuantity($id): JsonResponse
    {
        $cartDetail = CartDetail::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->where('id', $id)->first();

        if (!$cartDetail) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $cartDetail->increment('quantity');

        return response()->json(['message' => 'Quantity increased'], 200);
    }

    /**
     * Mengurangi jumlah produk dalam cart.
     */
    public function decrementQuantity($id): JsonResponse
    {
        $cartDetail = CartDetail::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->where('id', $id)->first();

        if (!$cartDetail) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        if ($cartDetail->quantity > 1) {
            $cartDetail->decrement('quantity');
        } else {
            $cartDetail->delete();
        }

        return response()->json(['message' => 'Quantity decreased'], 200);
    }

    /**
     * Menghapus produk dari cart.
     */
    public function removeFromCart($id): JsonResponse
    {
        $cartDetail = CartDetail::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->where('id', $id)->first();

        if (!$cartDetail) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $cartDetail->delete();

        return response()->json(['message' => 'Item removed from cart'], 200);
    }

    /**
     * Menghapus seluruh isi cart.
     */
    public function clearCart(): JsonResponse
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->cartDetails->isEmpty()) {
            return response()->json(['message' => 'Cart is already empty'], 200);
        }

        $cart->cartDetails()->delete();

        return response()->json(['message' => 'Cart cleared'], 200);
    }
}

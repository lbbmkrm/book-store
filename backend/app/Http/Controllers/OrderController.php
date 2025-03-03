<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        // dd($orders->toArray());
        return response()->json(OrderResource::collection($orders));
    }


    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json(new OrderResource($order));
    }

    public function store()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart || $cart->cartDetails->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $cart->cartDetails->sum(fn($item) => $item->book->price * $item->quantity),
            'status' => 'processing'
        ]);

        foreach ($cart->cartDetails as $cartDetail) {
            OrderDetail::create([
                'order_id' => $order->id,
                'book_id' => $cartDetail->book_id,
                'quantity' => $cartDetail->quantity,
                'price' => $cartDetail->book->price,
            ]);
        }

        $cart->cartDetails()->delete();
        $cart->delete();

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($order)
        ]);
    }
}

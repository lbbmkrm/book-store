<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\OrdersResource;
use App\Models\Order;
use App\Service\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            $orders = $this->orderService->getUserOrders($user->id);
            $message = $orders->isEmpty() ? 'No orders found' : 'Success';
            return $this->successResponse(
                $message,
                OrdersResource::collection($orders)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $this->isAuthorized('view', Order::class);
            $order = $this->orderService->getOrder($id);
            return $this->successResponse(
                'Success',
                new OrderResource($order)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedRequest = $request->validate([
                'shipping_address' => 'required|string|max:255'
            ]);
            $user = $this->getCurrentUser();
            $order = $this->orderService->createOrder($user->id, $validatedRequest);
            return $this->successResponse(
                'Success',
                new OrderResource($order),
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}

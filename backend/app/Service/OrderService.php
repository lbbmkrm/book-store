<?php

namespace App\Service;

use Exception;
use App\Models\Order;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\CartDetailRepository;
use App\Repository\OrderDetailRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected CartRepository $cartRepo;
    protected OrderRepository $orderRepo;
    protected OrderDetailRepository $orderDetailRepository;
    public function __construct(
        CartRepository $cart,
        OrderRepository $order,
        OrderDetailRepository $orderDetailRepository
    ) {
        $this->cartRepo = $cart;
        $this->orderRepo = $order;
        $this->orderDetailRepository = $orderDetailRepository;
    }

    public function getUserOrders(int $userId): Collection
    {
        try {
            return $this->orderRepo->getByUserId($userId);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed to retrieve orders',
                $e->getCode() ?: 500
            );
        }
    }

    public function getOrder(int $orderId, $relations = []): ?Order
    {
        try {
            return $this->orderRepo->get($orderId, $relations);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed to retrieve order',
                $e->getCode() ?: 500
            );
        }
    }

    public function createOrder(int $userId, array $requestData)
    {
        try {
            DB::beginTransaction();
            $cart = $this->cartRepo->findByUserId($userId);
            if (!$cart || $cart->cartDetails->isEmpty()) {
                throw new Exception('Cart is empty!', 404);
            }

            foreach ($cart->cartDetails as $detail) {
                $book = $detail->book()->lockForUpdate()->first();
                if ($book->stock < $detail->quantity) {
                    throw new Exception("Insufficient stock for book: {$book->title}", 400);
                }
            }

            $totalPrice = $cart->cartDetails->sum(function ($detail) {
                return $detail->quantity * $detail->book->price;
            });

            $order = $this->orderRepo->create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'shipping_address' => $requestData['shipping_address'],
                'status' => 'shipping'
            ]);

            foreach ($cart->cartDetails as $detail) {
                $this->orderDetailRepository->create([
                    'order_id' => $order->id,
                    'book_id' => $detail->book_id,
                    'quantity' => $detail->quantity,
                    'price' => $detail->book->price
                ]);
                $detail->book->stock -= $detail->quantity;
            }

            $cart->cartDetails()->delete();
            DB::commit();
            return $order->load('orderDetails.book');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Failed to create order',
                $e->getCode() ?: 500
            );
        }
    }

    public function updateStatusOrder(int $orderId, string $status): Order
    {
        try {
            DB::beginTransaction();
            $order = $this->orderRepo->get($orderId);
            $updatedOrder = $this->orderRepo->updateStatus($order, $status);
            DB::commit();
            return $updatedOrder;
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Failed to update order status.',
                $e->getCode() ?: 500
            );
        }
    }
}

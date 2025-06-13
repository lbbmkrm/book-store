<?php

namespace App\Repository;

use Exception;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;

class OrderRepository
{
    protected Order $model;
    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function getByUserId(int $id): ?Collection
    {
        try {
            return $this->model->with('orderDetails.book')->where('user_id', $id)
                ->orderBy('created_at', 'desc')->get();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Order not found.', 404);
        } catch (QueryException $e) {
            throw new Exception('Failed to retrieve orders due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve orders', 500);
        }
    }

    public function get(int $id, $relations = []): Order
    {
        try {
            return $this->model->with($relations)->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Order not found.', 404);
        } catch (QueryException $e) {
            throw new Exception('Failed to retrieve orders due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve order.', 500);
        }
    }

    public function create(array $data): Order
    {
        try {
            $order = $this->model->create($data);
            return $order;
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid data provided for creating order.', 422);
        } catch (QueryException $e) {
            throw new Exception('Failed to create order due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to create order.', 500);
        }
    }

    public function updateStatus(Order $order, string $status): Order
    {
        try {
            $order->update([
                'status' => $status
            ]);
            return $order;
        } catch (QueryException $e) {
            throw new Exception('Failed to update order status due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to update order status.', 500);
        }
    }
}

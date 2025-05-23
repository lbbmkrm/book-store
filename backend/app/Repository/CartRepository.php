<?php

namespace App\Repository;

use App\Models\Cart;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CartRepository
{
    protected Cart $model;

    public function __construct(Cart $cart)
    {
        $this->model = $cart;
    }
    public function findByUserId(int $id): ?Cart
    {
        try {
            $cart = $this->model->with('cartDetails.book')->where('user_id', $id)->first();
            return $cart;
        } catch (Exception $e) {
            throw new Exception('Failed to get cart: ' . $e->getMessage(), 500);
        }
    }
    public function create(array $data): ?Cart
    {
        try {
            $cart = $this->model->create($data);
            return $cart;
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                throw new Exception('A cart already exists for this user', 409);
            }
            throw new Exception('Failed to create cart: ' . $e->getMessage(), 500);
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid data provided', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to create cart: ' . $e->getMessage(), 500);
        }
    }
    public function deleteDuplicates(int $userId, int $keepId): void
    {
        try {
            $this->model->where('user_id', $userId)
                ->where('id', '!=', $keepId)
                ->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete duplicate carts: ' . $e->getMessage(), 500);
        }
    }
}

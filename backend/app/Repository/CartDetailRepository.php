<?php

namespace App\Repository;

use App\Models\CartDetail;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CartDetailRepository
{
    protected CartDetail $model;

    public function __construct(CartDetail $cartDetail)
    {
        $this->model = $cartDetail;
    }
    public function find(int $id): ?CartDetail
    {
        try {
            $cartDetail = $this->model->with('book')->findOrFail($id);
            return $cartDetail;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Cart item not found', 404);
        } catch (Exception $e) {
            throw new Exception('Failed to get cart item: ' . $e->getMessage(), 500);
        }
    }
    public function create(array $data): ?CartDetail
    {
        try {
            $cartDetail = $this->model->create($data);
            return $cartDetail;
        } catch (QueryException $e) {
            throw new Exception('Failed to add item to cart: ' . $e->getMessage(), 500);
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid data provided', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to add item to cart: ' . $e->getMessage(), 500);
        }
    }
    public function update(int $id, array $data): bool
    {
        try {
            $cartDetail = $this->model->findOrFail($id);
            return $cartDetail->update($data);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Cart item not found', 404);
        } catch (QueryException $e) {
            throw new Exception('Failed to update cart item: ' . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception('Failed to update cart item: ' . $e->getMessage(), 500);
        }
    }
    public function delete(int $id): bool
    {
        try {
            $cartDetail = $this->model->findOrFail($id);
            return $cartDetail->delete();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Cart item not found', 404);
        } catch (Exception $e) {
            throw new Exception('Failed to delete cart item: ' . $e->getMessage(), 500);
        }
    }
}

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

    /**
     * Find a cart detail by ID.
     *
     * @param int $id
     * @return CartDetail|null
     * @throws Exception
     */
    public function find(int $id): ?CartDetail
    {
        try {
            $cartDetail = $this->model->findOrFail($id);
            return $cartDetail;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Cart item not found', 404);
        } catch (Exception $e) {
            throw new Exception('Failed to get cart item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create a new cart detail.
     *
     * @param array $data
     * @return CartDetail|null
     * @throws Exception
     */
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

    /**
     * Update a cart detail.
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
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

    /**
     * Delete a cart detail.
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
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

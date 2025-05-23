<?php

namespace App\Service;

use Exception;
use App\Models\Book;
use App\Models\Cart;
use App\Repository\CartRepository;
use App\Repository\CartDetailRepository;
use Illuminate\Support\Facades\DB;

class CartService
{
    protected CartRepository $cartRepository;
    protected CartDetailRepository $cartDetailRepository;

    public function __construct(CartRepository $cartRepository, CartDetailRepository $cartDetailRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->cartDetailRepository = $cartDetailRepository;
    }

    public function getCart(int $userId): ?Cart
    {
        DB::beginTransaction();
        try {
            $cart = $this->cartRepository->findByUserId($userId);
            if (!$cart) {
                $cart = $this->cartRepository->create(['user_id' => $userId]);
            }
            $cart = $cart->load('cartDetails.book');
            DB::commit();
            return $cart;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to retrieve cart: " . $e->getMessage(), $e->getCode());
        }
    }

    public function addItem(int $userId, int $bookId, int $quantity): array
    {
        try {
            if ($quantity <= 0) {
                throw new Exception('Quantity must be greater than 0', 400);
            }
            DB::beginTransaction();
            $cart = $this->getCart($userId);

            $existingDetail = $cart->cartDetails()->where('book_id', $bookId)->first();
            if ($existingDetail) {
                $newQuantity = $existingDetail->quantity + $quantity;
                $book = Book::where('id', $existingDetail->book_id)->lockForUpdate()->firstOrFail();
                if ($book->stock < $newQuantity) {
                    throw new Exception('Requested quantity exceeds stock', 400);
                }
                $this->cartDetailRepository->update($existingDetail->id, ['quantity' => $newQuantity]);
                DB::commit();
                return ['message' => 'Quantity updated'];
            }

            $book = Book::where('id', $bookId)->lockForUpdate()->firstOrFail();
            if ($book->stock < $quantity) {
                throw new Exception('Requested quantity exceeds stock', 400);
            }
            $this->cartDetailRepository->create([
                'cart_id' => $cart->id,
                'book_id' => $bookId,
                'quantity' => $quantity,
            ]);
            DB::commit();
            return ['message' => 'Item added to cart'];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateItem(int $cartDetailId, int $quantity): array
    {
        DB::beginTransaction();
        try {
            $cartDetail = $this->cartDetailRepository->find($cartDetailId);
            $book = $cartDetail->book;
            if ($book->stock < $quantity) {
                throw new Exception('Requested quantity exceeds stock', 400);
            }
            $cartDetail->quantity = $quantity;
            $cartDetail->save();
            DB::commit();
            return [
                'message' => 'Item updated',
                'data' => $cartDetail
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Failed to update item',
                $e->getCode() ?: 500
            );
        }
    }

    public function removeItem(int $cartDetailId): array
    {
        DB::beginTransaction();
        try {
            $cartDetail = $this->cartDetailRepository->find($cartDetailId);
            $cartDetail->delete();
            DB::commit();
            return [
                'message' => 'Item removed successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Failed to remove item',
                $e->getCode() ?: 500
            );
        }
    }

    public function clearCart(int $userId): array
    {
        DB::beginTransaction();
        try {
            $cart = $this->getCart($userId);
            $cart->cartDetails()->delete();
            DB::commit();
            return [
                'message' => 'Cart cleared'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Failed to clear cart',
                $e->getCode() ?: 500
            );
        }
    }
}

<?php

namespace App\Service;

use Exception;
use App\Models\Book;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Repository\CartRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repository\CartDetailRepository;

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
            throw new Exception("Failed to retrieve cart for user ID {$userId}", $e->getCode() ?: 500);
        }
    }

    public function getCartDetail(int $cartDetailId): ?CartDetail
    {
        try {
            $cartDetail =  $this->cartDetailRepository->find($cartDetailId)->load('cart');
            return $cartDetail;
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Failed to get cart detail.',
                $e->getCode() ?: 500
            );
        }
    }

    public function addItem(int $userId, int $bookId, int $quantity): Cart
    {
        DB::beginTransaction();
        try {
            $cart = $this->getCart($userId);
            $existingDetail = $cart->cartDetails()->where('book_id', $bookId)->first();
            if ($existingDetail) {
                $newQuantity = $existingDetail->quantity + $quantity;
                $book = Book::where('id', $existingDetail->book_id)->lockForUpdate()->firstOrFail();
                if ($book->stock < $newQuantity) {
                    throw new Exception("Requested quantity for book ID {$bookId} exceeds available stock", 400);
                }
                $this->cartDetailRepository->update($existingDetail->id, ['quantity' => $newQuantity]);
                DB::commit();
                return $cart->fresh();
            }

            $book = Book::where('id', $bookId)->lockForUpdate()->firstOrFail();
            if ($book->stock < $quantity) {
                throw new Exception("Requested quantity for book ID {$bookId} exceeds available stock", 400);
            }
            $this->cartDetailRepository->create([
                'cart_id' => $cart->id,
                'book_id' => $bookId,
                'quantity' => $quantity,
            ]);
            DB::commit();
            return $cart->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: "Failed to add item to cart for book ID {$bookId}",
                $e->getCode() ?: 500
            );
        }
    }

    public function updateItem(int $cartDetailId, int $quantity): Cart
    {
        DB::beginTransaction();
        try {
            $cartDetail = $this->cartDetailRepository->find($cartDetailId);
            $book = $cartDetail->book()->lockForUpdate()->first();
            if ($book->stock < $quantity) {
                throw new Exception("Requested quantity for cart item ID {$cartDetailId} exceeds available stock", 400);
            }
            $this->cartDetailRepository->update($cartDetailId, ['quantity' => $quantity]);
            DB::commit();
            return $cartDetail->cart->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: "Failed to update cart item ID {$cartDetailId}",
                $e->getCode() ?: 500
            );
        }
    }

    public function removeItem(int $cartDetailId): Cart
    {
        DB::beginTransaction();
        try {
            $cartDetail = $this->cartDetailRepository->find($cartDetailId);
            $cart = $cartDetail->cart;
            $cartDetail->delete();
            DB::commit();
            return $cart->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: "Failed to remove cart item ID {$cartDetailId}",
                $e->getCode() ?: 500
            );
        }
    }

    public function clearCart(int $userId): Cart
    {
        DB::beginTransaction();
        try {
            $cart = $this->getCart($userId);
            $cart->cartDetails()->delete();
            DB::commit();
            return $cart->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: "Failed to clear cart for user ID {$userId}",
                $e->getCode() ?: 500
            );
        }
    }
}

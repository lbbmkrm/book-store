<?php

namespace App\Http\Controllers;

use Exception;
use App\Service\CartService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Cart\CartResource;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;

class CartController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            $cart = $this->cartService->getCart($user->id);
            return $this->successResponse(
                'Cart retrieved successfully',
                new CartResource($cart)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function store(AddCartItemRequest $request): JsonResponse
    {
        try {
            $validatedReq = $request->validated();
            $user = $this->getCurrentUser();
            $cart = $this->cartService->addItem(
                $user->id,
                $validatedReq['book_id'],
                $validatedReq['quantity']
            );
            return $this->successResponse(
                'Cart item added successfully',
                new CartResource($cart),
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function update(UpdateCartItemRequest $request, int $cartDetailId): JsonResponse
    {
        try {
            $cartDetail = $this->cartService->getCartDetail($cartDetailId);
            $this->isAuthorized('update', $cartDetail);
            $validatedReq = $request->validated();
            $cart = $this->cartService->updateItem($cartDetailId, $validatedReq['quantity']);
            return $this->successResponse(
                'Cart item updated successfully',
                new CartResource($cart)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function destroy(int $cartDetailId): JsonResponse
    {
        try {
            $cartDetail = $this->cartService->getCartDetail($cartDetailId);
            $this->isAuthorized('delete', $cartDetail);
            $cart = $this->cartService->removeItem($cartDetailId);
            return $this->successResponse(
                'Cart item removed successfully',
                new CartResource($cart)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function clear(): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            $cart = $this->cartService->getCart($user->id);
            $this->isAuthorized('delete', $cart);
            $cart = $this->cartService->clearCart($user->id);
            return $this->successResponse(
                'Cart cleared successfully',
                new CartResource($cart)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}

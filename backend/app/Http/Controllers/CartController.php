<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Service\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use function App\Helpers\getCurrentUser;
use App\Http\Resources\Cart\CartResource;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Resources\Cart\CartDetailResource;
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
            $user =  getCurrentUser();
            $cart = $this->cartService->getCart($user->id);
            $this->isAuthorized('view', $cart);
            return $this->successResponse(
                'success retrieve cart',
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
            $user = getCurrentUser();
            $cart = $this->cartService->getCart($user->id);
            $this->isAuthorized('create', $cart);
            $result = $this->cartService->addItem(
                $user->id,
                $validatedReq['book_id'],
                $validatedReq['quantity']
            );
            $cart = $this->cartService->getCart($user->id);
            return $this->successResponse(
                $result['message'],
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
            $user = getCurrentUser();
            $cart = $this->cartService->getCart($user->id);
            $this->isAuthorized('update', $cart);
            $validatedReq = $request->validated();
            $result = $this->cartService->updateItem($cartDetailId, $validatedReq['quantity']);
            return $this->successResponse(
                $result['message'],
                new CartDetailResource($result['data'])
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }


    public function destroy(int $cartDetailId): JsonResponse
    {
        try {
            $user = getCurrentUser();
            $cart = $this->cartService->getCart($user->id);
            $this->isAuthorized('delete', $cart);
            $result = $this->cartService->removeItem($cartDetailId);
            return $this->successResponse(
                $result['message'],
                new CartResource($cart)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function clear(): JsonResponse
    {
        try {
            $user = getCurrentUser();
            $cart = $this->cartService->getCart($user->id);
            $this->isAuthorized('delete', $cart);
            $result = $this->cartService->clearCart($user->id);
            return $this->successResponse($result['message']);
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}

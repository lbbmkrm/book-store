<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Service\CategoryService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getAll();
            $message = $categories->isEmpty() ? 'no categories' : 'success';
            return response()->json([
                'message' => $message,
                'categories' => CategoryResource::collection($categories)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->get($id);
            return response()->json([
                'message' => 'success',
                'category' => new CategoryResource($category)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function create(CreateCategoryRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        try {
            $category = $this->categoryService->createCategory($validatedRequest);
            return response()->json([
                'message' => 'success',
                'newCategory' => new CategoryResource($category)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update(int $id, UpdateCategoryRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        try {
            $category = $this->categoryService->get($id);
            $this->authorize('update', $category);
            $updatedCategory = $this->categoryService->updateCategory($id, $validatedRequest);
            return response()->json([
                'message' => 'success',
                'updatedCategory' => new CategoryResource($updatedCategory)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->get($id);
            $this->authorize('delete', $category);
            $this->categoryService->categoryDelete($id);

            return response()->json([
                'message' => 'success'
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use App\Service\CategoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\Category\CreateCategoryRequest;

class CategoryController extends Controller
{
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
            return $this->successResponse(
                $message,
                CategoryResource::collection($categories)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
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
        $this->isAuthorized('create', Category::class);
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
        $category = $this->categoryService->get($id);
        $this->isAuthorized('update', $category);
        $validatedRequest = $request->validated();
        try {
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
        $category = $this->categoryService->get($id);
        $this->isAuthorized('delete', $category);
        try {
            $this->categoryService->categoryDelete($id);

            return response()->json([
                'message' => 'success'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}

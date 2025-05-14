<?php

namespace App\Service;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    protected CategoryRepository $categoryRepo;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    public function getAll(): ?Collection
    {
        try {
            $categories = $this->categoryRepo->categories();
            return $categories;
        } catch (Exception $e) {
            throw new Exception('failed to retrive category', 500);
        }
    }

    public function get(int $categoryId): ?Category
    {
        try {
            $category = $this->categoryRepo->category($categoryId);
            if (!$category) {
                throw new Exception('category not found', 404);
            }
            return $category;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'failed to get category', $e->getCode() ?: 500);
        }
    }

    public function createCategory(array $requestData): ?Category
    {
        try {

            if (isset($requestData['img'])) {
                $requestData['img'] = $requestData['img']->store('image/categories', 'public');
            } else {
                $requestData['img'] = null;
            }
            DB::beginTransaction();
            $newCategory = $this->categoryRepo->create($requestData);
            DB::commit();
            return $newCategory;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('failed to create category', 500);
        }
    }

    public function updateCategory(int $categoryId, array $requestData)
    {
        $category = $this->get($categoryId);
        try {
            if (Gate::denies('update', $category)) {
                throw new Exception('unauthorized', 403);
            }
            if (isset($requestData['img'])) {
                if ($category->img) {
                    Storage::disk('public')->delete($category->img);
                }
                $path = $requestData['img']->store('image/category', 'public');
                $requestData['img'] = $path;
            }
            DB::beginTransaction();
            $updatedCategory = $this->categoryRepo->update($category, $requestData);
            DB::commit();
            return $updatedCategory;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('failed to update', 500);
        }
    }

    public function categoryDelete(int $categoryId)
    {
        $category = $this->get($categoryId);
        try {
            if (Gate::denies('delete', $category)) {
                throw new Exception('unauthorized', 403);
            }
            DB::beginTransaction();
            $this->categoryRepo->delete($category);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('failed to delete', 500);
        }
    }
}

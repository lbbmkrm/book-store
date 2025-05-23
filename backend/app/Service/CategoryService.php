<?php

namespace App\Service;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
            return $this->categoryRepo->categories();
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Failed to retrieve categories.',
                $e->getCode() ?: 500
            );
        }
    }

    public function get(int $categoryId): ?Category
    {
        try {
            return $this->categoryRepo->category($categoryId);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Failed to get category.',
                $e->getCode() ?: 500
            );
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
            throw new Exception(
                $e->getMessage() ?: 'Failed to create category.',
                $e->getCode() ?: 500
            );
        }
    }

    public function updateCategory(int $categoryId, array $requestData): ?Category
    {
        $category = $this->get($categoryId);

        try {
            if (isset($requestData['img'])) {
                if ($category->img) {
                    Storage::disk('public')->delete($category->img);
                }

                $requestData['img'] = $requestData['img']->store('image/category', 'public');
            }

            DB::beginTransaction();
            $updatedCategory = $this->categoryRepo->update($category, $requestData);
            DB::commit();

            return $updatedCategory;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Failed to update category.',
                $e->getCode() ?: 500
            );
        }
    }

    public function categoryDelete(int $categoryId): void
    {
        $category = $this->get($categoryId);

        try {
            if ($category->books()->exists()) {
                throw new Exception('Cannot delete category with associated books.', 400);
            }

            DB::beginTransaction();
            $this->categoryRepo->delete($category);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Failed to delete category.',
                $e->getCode() ?: 500
            );
        }
    }
}

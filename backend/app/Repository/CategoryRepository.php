<?php

namespace App\Repository;

use App\Models\Category;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;

class CategoryRepository
{
    protected Category $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function categories(): ?Collection
    {
        try {
            return $this->model->all();
        } catch (QueryException $e) {
            throw new Exception('Failed to retrieve categories due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve categories.', 500);
        }
    }

    public function category(int $id): ?Category
    {
        try {
            return $this->model->where('id', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Category not found.', 404);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve category.', 500);
        }
    }

    public function create(array $data): ?Category
    {
        try {
            return $this->model->create($data);
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid data provided for creating category.', 422);
        } catch (QueryException $e) {
            throw new Exception('Failed to create category due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to create category.', 500);
        }
    }

    public function update(Category $category, array $data): ?Category
    {
        try {
            $category->update($data);
            return $category;
        } catch (QueryException $e) {
            throw new Exception('Failed to update category due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to update category.', 500);
        }
    }

    public function delete(Category $category): ?bool
    {
        try {
            return $category->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete category.', 500);
        }
    }
}

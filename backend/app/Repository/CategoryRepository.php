<?php

namespace App\Repository;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

class CategoryRepository
{
    protected Category $model;
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function categories(): ?Collection
    {
        return $this->model->all();
    }

    public function category(int $id): ?Category
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): ?Category
    {
        return $this->model->create($data);
    }

    public function update(Category $category, array $data): ?Category
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category): ?bool
    {
        return $category->delete();
    }
}

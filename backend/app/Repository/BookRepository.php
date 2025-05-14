<?php

namespace App\Repository;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class BookRepository
{
    private Book $model;
    public function __construct(Book $book)
    {
        $this->model = $book;
    }

    public function getAll(): ?Collection
    {
        return $this->model->all();
    }

    public function get(int $id): ?Book
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): ?Book
    {
        $newBook = $this->model->create($data);
        return $newBook;
    }

    public function update(Book $book, array $data): Book
    {
        $book->update($data);
        return $book;
    }

    public function delete(Book $book): ?bool
    {
        return $book->delete();
    }
}

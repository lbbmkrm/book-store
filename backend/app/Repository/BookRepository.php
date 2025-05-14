<?php

namespace App\Repository;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class BookRepository
{
    private Book $bookModel;
    public function __construct(Book $book)
    {
        $this->bookModel = $book;
    }

    public function getAll(): ?Collection
    {
        return $this->bookModel->all();
    }

    public function get(int $id): ?Book
    {
        return $this->bookModel->findOrFail($id);
    }
}

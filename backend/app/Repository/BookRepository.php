<?php

namespace App\Repository;

use Exception;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;

class BookRepository
{
    private Book $model;

    public function __construct(Book $book)
    {
        $this->model = $book;
    }

    public function getAll(): ?Collection
    {
        try {
            return $this->model->with('category')->get();
        } catch (QueryException $e) {
            throw new Exception('Failed to retrieve books due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve books.', 500);
        }
    }

    public function get(int $id): ?Book
    {
        try {
            return $this->model->with('category')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Book not found.', 404);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve book.', 500);
        }
    }

    public function create(array $data): ?Book
    {
        try {
            $newBook = $this->model->create($data);
            return $newBook;
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid data provided for creating book.', 422);
        } catch (QueryException $e) {
            throw new Exception('Failed to create book due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to create book.', 500);
        }
    }

    public function update(Book $book, array $data): Book
    {
        try {
            $book->update($data);
            return $book;
        } catch (QueryException $e) {
            throw new Exception('Failed to update book due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to update book.', 500);
        }
    }

    public function delete(Book $book): ?bool
    {
        try {
            return $book->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete book.', 500);
        }
    }

    public function getTopBooks()
    {
        try {
            return $this->model->inRandomOrder()->limit(5)->get();
        } catch (QueryException $e) {
            throw new Exception('Failed to retrieve books due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve books.', 500);
        }
    }
}

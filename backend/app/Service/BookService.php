<?php

namespace App\Service;

use Exception;
use App\Models\Book;
use App\Repository\BookRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BookService
{
    protected BookRepository $bookRepo;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepo = $bookRepository;
    }

    public function getAllBooks(): ?Collection
    {
        try {
            $books = $this->bookRepo->getAll();
            return $books;
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed to get books',
                $e->getCode() ?: 500
            );
        }
    }

    public function getBook(int $bookId): ?Book
    {
        try {
            $book =  $this->bookRepo->get($bookId);
            return $book;
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed to get book',
                $e->getCode() ?: 500
            );
        }
    }

    public function createBook(array $requestData): ?Book
    {
        if (isset($requestData['img'])) {
            $requestData['img'] = $requestData['img']->store('image/books', 'public');
        } else {
            $requestData['img'] = null;
        }
        try {
            DB::beginTransaction();
            $newBook = $this->bookRepo->create($requestData);
            DB::commit();
            return $newBook;
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed to create book',
                $e->getCode() ?: 500
            );
        }
    }

    public function updateBook(int $bookId, array $requestData): Book
    {
        $book = $this->getBook($bookId);
        try {
            if (isset($requestData['img'])) {
                if ($book->img) {
                    Storage::disk('public')->delete($book->img);
                }
                $requestData['img'] = $requestData['img']->store('image/books', 'public');
            }
            if (isset($requestData['stock']) && $requestData['stock'] < 0) {
                throw new Exception('Stock cannot be negative', 400);
            }
            DB::beginTransaction();
            $updatedBook = $this->bookRepo->update($book, $requestData);
            DB::commit();
            return $updatedBook;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'failed to update book',
                $e->getCode() ?: 500
            );
        }
    }

    public function deleteBook(int $bookId): void
    {
        $book = $this->getBook($bookId);
        try {
            if (Gate::denies('delete', $book)) {
                throw new Exception('unauthorized', 403);
            }
            DB::beginTransaction();
            $this->bookRepo->delete($book);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'failed delete book',
                $e->getCode() ?: 500
            );
        }
    }

    public function getTopBooks()
    {
        try {
            $books = $this->bookRepo->getTopBooks();
            return $books;
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed to get top books',
                $e->getCode() ?: 500
            );
        }
    }
}

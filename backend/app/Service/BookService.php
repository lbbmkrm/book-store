<?php

namespace App\Service;

use Exception;
use App\Models\Book;
use App\Repository\BookRepository;
use Illuminate\Database\Eloquent\Collection;

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
            throw new Exception('failed to get books', 500);
        }
    }

    public function getBook(int $bookId): ?Book
    {
        try {
            $book =  $this->bookRepo->get($bookId);
            if (!$book) {
                throw new Exception('book not found', 404);
            }
            return $book;
        } catch (Exception $e) {
            throw new Exception('failed to get book', 500);
        }
    }
}

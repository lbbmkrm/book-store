<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\CategoryResource;
use App\Service\BookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class BookController extends Controller
{
    private BookService $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    public function index(): JsonResponse
    {
        try {
            $books = $this->bookService->getAllBooks();
            $message = $books->isEmpty() ? 'no books' : 'success';
            return response()->json([
                'message' => $message,
                'books' => BookResource::collection($books)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $book = $this->bookService->getBook($id);
            return response()->json([
                'message' => 'success',
                'book' => new BookResource($book)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}

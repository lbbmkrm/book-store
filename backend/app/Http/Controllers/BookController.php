<?php

namespace App\Http\Controllers;

use Exception;
use App\Service\BookService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BookResource;
use App\Http\Requests\Book\CreateBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Models\Book;

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

    public function create(CreateBookRequest $request): JsonResponse
    {
        $this->isAuthorized('create', Book::class);
        $validatedRequest = $request->validated();
        try {
            $newBook = $this->bookService->createBook($validatedRequest);
            return response()->json([
                'message' => 'success',
                'newBook' => new BookResource($newBook)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update(UpdateBookRequest $request, int $id): JsonResponse
    {
        $book = $this->bookService->getBook($id);
        $this->isAuthorized('update', $book);
        $validatedRequest = $request->validated();
        try {
            $updatedBook = $this->bookService->updateBook($id, $validatedRequest);
            return response()->json([
                'message' => 'success',
                'book' => new BookResource($updatedBook)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function delete(int $id): JsonResponse
    {
        $book = $this->bookService->getBook($id);
        $this->authorize('delete', $book);
        try {
            $this->bookService->deleteBook($id);
            return response()->json([
                'message' => 'success delete book'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}

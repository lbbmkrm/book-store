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
            return $this->successResponse(
                $message,
                BookResource::collection($books)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
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
        try {
            $validatedRequest = $request->validated();
            $this->isAuthorized('create', Book::class);
            $book = $this->bookService->createBook($validatedRequest);
            return $this->successResponse(
                'Book created successfully',
                new BookResource($book),
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function update(UpdateBookRequest $request, int $id): JsonResponse
    {
        try {
            $book = $this->bookService->getBook($id);
            $this->isAuthorized('update', $book);
            $validatedRequest = $request->validated();
            $updatedBook = $this->bookService->updateBook($id, $validatedRequest);
            return $this->successResponse('Book updated successfully', new BookResource($updatedBook));
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $book = $this->bookService->getBook($id);
            $this->isAuthorized('delete', $book);
            $this->bookService->deleteBook($id);
            return $this->successResponse('Book deleted successfully');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function topBooks(): JsonResponse
    {
        try {
            $books = $this->bookService->getTopBooks();
            $message = $books->isEmpty() ? 'No top books found' : 'success';
            return $this->successResponse(
                $message,
                BookResource::collection($books)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}

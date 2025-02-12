<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookController extends Controller
{
    public function index(): JsonResource
    {
        $books = Book::all();
        return BookResource::collection($books);
    }

    public function show($id): JsonResource
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Book not avaible'
            ], 404);
        }

        return new BookResource($book);
    }
}

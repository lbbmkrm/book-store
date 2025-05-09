<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\CategoryResource;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookController extends Controller
{
    public function index(): JsonResource
    {
        $books = Book::all();
        return BookResource::collection($books);
    }

    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Book not avaible'
            ], 404);
        }

        return new BookResource($book);
    }

    public function categories()
    {
        $categories = Category::all();
        return  CategoryResource::collection($categories);
    }

    public function topProducts()
    {
        $products = Book::orderBy('stock', 'asc')->take(4)->get();
        return BookResource::collection($products);
    }
}

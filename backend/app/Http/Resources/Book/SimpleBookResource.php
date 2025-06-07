<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'category' => $this->category->name,
            'stock' => $this->stock,
            'price' => $this->price,
            'img' => $this->img
        ];
    }
}

<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Book\SimpleBookResource;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartDetailResource extends JsonResource
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
            'cart_id' => $this->cart_id,
            'book_id' => $this->book_id,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'book' => new SimpleBookResource($this->whenLoaded('book')),
        ];
    }
}

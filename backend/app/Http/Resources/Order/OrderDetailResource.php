<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Book\SimpleBookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'orderId' => $this->order_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'updatedAt' => $this->updated_at,
            'book' => new SimpleBookResource($this->whenLoaded('book'))
        ];
    }
}

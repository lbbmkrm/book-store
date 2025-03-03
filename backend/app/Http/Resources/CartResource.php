<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = [];
        $totalItems = 0;
        $totalPrice = 0;

        foreach ($this->cartDetails as $item) {
            $subtotal = $item->quantity * $item->product->price;
            $totalItems += $item->quantity;
            $totalPrice += $subtotal;

            $items[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'image' => asset('storage/' . $item->product->image),
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'subtotal' => $subtotal
            ];
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'items' => $items
        ];
    }
}

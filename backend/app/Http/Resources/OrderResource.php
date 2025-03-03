<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
            'details' => $this->orderDetails->map(function ($detail) {
                return [
                    'book_id' => $detail->book_id,
                    'book_name' => optional($detail->book)->title,
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                ];
            }),
        ];
    }
}

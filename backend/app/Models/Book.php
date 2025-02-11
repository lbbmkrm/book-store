<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'description',
        'stock',
        'price',
        'img'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function cartDetails(): HasMany
    {
        return $this->hasMany(CartDetail::class, 'book_id', 'id');
    }
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'book_id', 'id');
    }
}

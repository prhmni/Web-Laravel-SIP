<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'book_code',
        'title',
        'isbn',
        'author',
        'publisher',
        'publish_year',
        'stock',
        'available_stock',
        'shelf_location',
        'cover_image',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowDetails(): HasMany
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image && file_exists(public_path('storage/' . $this->cover_image))) {
            return asset('storage/' . $this->cover_image);
        }
        return asset('images/book-placeholder.svg');
    }
}

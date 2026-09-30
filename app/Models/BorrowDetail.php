<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_id',
        'book_id',
        'return_date',
        'status',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function borrow(): BelongsTo
    {
        return $this->belongsTo(Borrow::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}

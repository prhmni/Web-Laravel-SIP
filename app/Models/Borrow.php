<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'member_id',
        'user_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'late_days',
        'late_fee',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'late_fee' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public static function generateCode(): string
    {
        $ymd = date('Ymd');
        $prefix = "PJ-{$ymd}-";
        $lastBorrow = self::where('transaction_code', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastBorrow) {
            $lastNumber = (int) substr($lastBorrow->transaction_code, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Hitung keterlambatan dan denda berdasarkan tanggal kembali (atau hari ini jika belum kembali)
     */
    public function getEstimatedLateDaysAttribute(): int
    {
        if ($this->status === 'dikembalikan' && $this->return_date) {
            $endDate = Carbon::parse($this->return_date);
        } else {
            $endDate = Carbon::today();
        }

        $due = Carbon::parse($this->due_date);
        if ($endDate->gt($due)) {
            return $endDate->diffInDays($due);
        }

        return 0;
    }
}

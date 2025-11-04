<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'admin_id',
        'status',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta' => 'array',
    ];

    /**
     * Relasi ke Wallet
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Admin (yang melakukan topup)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi polymorphic ke reference (Sale, etc)
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        $prefix = in_array($this->type, ['topup', 'refund']) ? '+' : '-';
        return $prefix . ' Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get transaction type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'topup' => 'Top Up',
            'payment' => 'Pembayaran',
            'refund' => 'Refund',
            'adjustment' => 'Penyesuaian',
            default => 'Unknown',
        };
    }

    /**
     * Get transaction type color
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'topup' => 'green',
            'payment' => 'red',
            'refund' => 'blue',
            'adjustment' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Scope untuk filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope untuk completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

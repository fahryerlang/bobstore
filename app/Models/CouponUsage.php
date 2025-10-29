<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponUsage extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'coupon_usage';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'coupon_id',
        'customer_id',
        'sale_id',
        'used_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'used_at' => 'datetime',
    ];

    /**
     * Coupon associated with the usage record.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Customer who used the coupon.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Sale linked to this coupon usage (when available).
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}

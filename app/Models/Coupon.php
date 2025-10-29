<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'discount_id',
        'code',
        'name',
        'description',
        'usage_limit',
        'per_customer_limit',
        'min_order_value',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'usage_limit' => 'integer',
        'per_customer_limit' => 'integer',
        'min_order_value' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Coupon belongs to a discount campaign.
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Usage records for this coupon.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Scope to only include currently active coupons.
     */
    public function scopeActive($query)
    {
        $now = Carbon::now();

        return $query
            ->where('is_active', true)
            ->where(function ($query) use ($now) {
                $query
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            });
    }

    /**
     * Determine if coupon can be used at the given time.
     */
    public function isCurrentlyActive(?CarbonInterface $at = null): bool
    {
        $at = $at ?: Carbon::now();

        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the coupon has remaining uses globally.
     */
    public function hasRemainingGlobalUses(): bool
    {
        if ($this->usage_limit === null) {
            return true;
        }

        return $this->usages()->count() < $this->usage_limit;
    }

    /**
     * Check whether the coupon has remaining uses for a specific customer.
     */
    public function hasRemainingUsesFor(User $customer): bool
    {
        if ($this->per_customer_limit === null) {
            return true;
        }

        $usageCount = $this->usages()
            ->where('customer_id', $customer->id)
            ->count();

        return $usageCount < $this->per_customer_limit;
    }

    /**
     * Determine if the coupon can be applied for the given customer and order total.
     */
    public function canBeUsedBy(User $customer, float $orderTotal): bool
    {
        if (! $this->isCurrentlyActive()) {
            return false;
        }

        if (! $this->hasRemainingGlobalUses()) {
            return false;
        }

        if (! $this->hasRemainingUsesFor($customer)) {
            return false;
        }

        if ($orderTotal < (float) $this->min_order_value) {
            return false;
        }

        if (! $this->discount || ! $this->discount->isCurrentlyActive()) {
            return false;
        }

        return true;
    }
}

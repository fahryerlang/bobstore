<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'applies_automatically',
        'is_stackable',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'applies_automatically' => 'boolean',
        'is_stackable' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the rules configured for this discount.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(DiscountRule::class);
    }

    /**
     * Get coupons associated with this discount.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Scope query to only include discounts that are active right now.
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
     * Determine whether the discount is active at the moment.
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
}

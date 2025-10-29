<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DiscountRule extends Model
{
    use HasFactory;

    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'discount_id',
        'product_id',
        'category_id',
        'discount_type',
        'discount_value',
        'min_quantity',
        'priority',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_quantity' => 'integer',
        'priority' => 'integer',
    ];

    /**
     * Belongs to a discount campaign.
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Targeted product for the rule (if any).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Targeted category for the rule (if any).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope query to only include rules whose parent discount is active now.
     */
    public function scopeActive($query)
    {
        $now = Carbon::now();

        return $query->whereHas('discount', function ($query) use ($now) {
            $query->where('is_active', true)
                ->where(function ($query) use ($now) {
                    $query->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', $now);
                })
                ->where(function ($query) use ($now) {
                    $query->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', $now);
                });
        });
    }

    /**
     * Resolve the highest-priority applicable rule for the given product.
     */
    public static function resolveForProduct(Product $product, int $quantity = 1, array $options = []): ?self
    {
        $options = array_merge([
            'only_automatic' => true,
            'discount_id' => null,
        ], $options);

        $query = static::query()->with('discount');

        $query->where(function ($query) use ($product) {
            $query->where('product_id', $product->id);

            if ($product->category_id) {
                $query->orWhere(function ($query) use ($product) {
                    $query->whereNull('product_id')
                        ->where('category_id', $product->category_id);
                });
            }

            $query->orWhere(function ($query) {
                $query->whereNull('product_id')->whereNull('category_id');
            });
        });

        if ($options['discount_id']) {
            $query->where('discount_id', $options['discount_id']);
        }

        $query->whereHas('discount', function ($query) use ($options) {
            $query->active();

            if ($options['only_automatic']) {
                $query->where('applies_automatically', true);
            }
        });

        $rules = $query
            ->orderByDesc('priority')
            ->orderByRaw('product_id IS NOT NULL DESC')
            ->orderByRaw('category_id IS NOT NULL DESC')
            ->orderBy('id')
            ->get();

        $quantity = max(1, (int) $quantity);

        foreach ($rules as $rule) {
            if ($quantity < $rule->min_quantity) {
                continue;
            }

            return $rule;
        }

        return null;
    }

    /**
     * Calculate the discount amount for the provided base price and quantity.
     */
    public function calculateDiscountAmount(float $unitBasePrice, int $quantity = 1): float
    {
        $quantity = max(1, (int) $quantity);

        if ($quantity < $this->min_quantity) {
            return 0.0;
        }

        $unitBasePrice = max(0, $unitBasePrice);
        $baseTotal = $unitBasePrice * $quantity;

        if ($baseTotal <= 0) {
            return 0.0;
        }

        if ($this->discount_type === self::TYPE_PERCENTAGE) {
            $percentage = max(0, min(100, (float) $this->discount_value));
            return round($baseTotal * ($percentage / 100), 2);
        }

        // Fixed amount is applied per unit
        $discount = (float) $this->discount_value * $quantity;

        return round(min($discount, $baseTotal), 2);
    }

    /**
     * Build a pricing summary using the product's list price as base.
     */
    public function buildPricingForProduct(Product $product, int $quantity = 1): array
    {
        return $this->buildPricing((float) $product->harga, $quantity);
    }

    /**
     * Build a pricing summary based on the provided unit base price.
     */
    public function buildPricing(float $unitBasePrice, int $quantity = 1): array
    {
        $quantity = max(1, (int) $quantity);
        $unitBasePrice = max(0, $unitBasePrice);
        $baseTotalPrice = $unitBasePrice * $quantity;

        $discountAmount = $this->calculateDiscountAmount($unitBasePrice, $quantity);
        $discountAmount = min($discountAmount, $baseTotalPrice);

        $unitDiscount = $quantity > 0 ? $discountAmount / $quantity : 0;
        $unitFinalPrice = max(0, $unitBasePrice - $unitDiscount);
        $finalTotalPrice = max(0, $baseTotalPrice - $discountAmount);

        $percentage = $unitBasePrice <= 0 ? 0.0 : ($unitDiscount / $unitBasePrice) * 100;

        return [
            'rule' => $this,
            'base_unit_price' => round($unitBasePrice, 2),
            'base_total_price' => round($baseTotalPrice, 2),
            'unit_price' => round($unitFinalPrice, 2),
            'total_price' => round($finalTotalPrice, 2),
            'unit_discount' => round($unitDiscount, 2),
            'total_discount' => round($discountAmount, 2),
            'discount_percentage' => round($percentage, 2),
        ];
    }
}

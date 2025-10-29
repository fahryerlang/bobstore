<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_barang',
        'harga',
        'stok',
        'gambar',
        'category_id',
        'subcategory_id',
    ];

    /**
     * Cached discount summaries keyed by context.
     *
     * @var array<string, array>
     */
    protected array $discountSummaryCache = [];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the subcategory that owns the product
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Get tags for this product
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag', 'product_id', 'tag_id');
    }

    /**
     * Discount rules specifically attached to this product.
     */
    public function discountRules(): HasMany
    {
        return $this->hasMany(DiscountRule::class);
    }

    /**
     * Get active discount rules for this product
     */
    public function activeDiscountRules(): HasMany
    {
        return $this->discountRules()
            ->whereHas('discount', function ($query) {
                $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                            ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('ends_at')
                            ->orWhere('ends_at', '>=', now());
                    });
            });
    }

    /**
     * Return the automatic discount summary for the given quantity.
     */
    public function discountSummary(int $quantity = 1): array
    {
        $quantity = max(1, (int) $quantity);
        $cacheKey = $quantity.':auto';

        if (isset($this->discountSummaryCache[$cacheKey])) {
            return $this->discountSummaryCache[$cacheKey];
        }

        $baseUnitPrice = (float) $this->harga;
        $baseTotalPrice = $baseUnitPrice * $quantity;

        $summary = [
            'applies' => false,
            'rule' => null,
            'base_unit_price' => $baseUnitPrice,
            'base_total_price' => $baseTotalPrice,
            'unit_price' => $baseUnitPrice,
            'total_price' => $baseTotalPrice,
            'unit_discount' => 0.0,
            'total_discount' => 0.0,
            'discount_percentage' => 0.0,
        ];

        $rule = DiscountRule::resolveForProduct($this, $quantity, [
            'only_automatic' => true,
        ]);

        if ($rule) {
            $pricing = $rule->buildPricingForProduct($this, $quantity);

            $summary = array_merge($summary, $pricing);
            $summary['applies'] = $pricing['total_discount'] > 0;
        }

        return $this->discountSummaryCache[$cacheKey] = $summary;
    }

    /**
     * Determine whether automatic discount applies.
     */
    public function hasAutomaticDiscount(int $quantity = 1): bool
    {
        $summary = $this->discountSummary($quantity);

        return $summary['applies'];
    }

    /**
     * Get discounted unit price after automatic rules.
     */
    public function discountedUnitPrice(int $quantity = 1): float
    {
        $summary = $this->discountSummary($quantity);

        return (float) $summary['unit_price'];
    }

    /**
     * Get total price for quantity after automatic rules.
     */
    public function discountedTotalPrice(int $quantity = 1): float
    {
        $summary = $this->discountSummary($quantity);

        return (float) $summary['total_price'];
    }

    /**
     * Get automatic discount amount for the given quantity.
     */
    public function automaticDiscountAmount(int $quantity = 1): float
    {
        $summary = $this->discountSummary($quantity);

        return (float) $summary['total_discount'];
    }

    /**
     * Get automatic discount percentage for a single unit.
     */
    public function automaticDiscountPercentage(): float
    {
        $summary = $this->discountSummary(1);

        return (float) $summary['discount_percentage'];
    }

    /**
     * Get available discount info (regardless of quantity requirement)
     * This is useful for displaying "discount available" badges
     */
    public function availableDiscountInfo(): ?array
    {
        // Get the best discount rule available for this product
        $rule = $this->activeDiscountRules()
            ->whereHas('discount', function ($query) {
                $query->where('applies_automatically', true);
            })
            ->orderBy('priority', 'desc')
            ->orderBy('discount_value', 'desc')
            ->first();

        if (!$rule) {
            return null;
        }

        $discountValue = (float) $rule->discount_value;
        $discountType = $rule->discount_type;
        $minQuantity = (int) $rule->min_quantity;

        // Calculate discount percentage for display
        $discountPercentage = 0;
        if ($discountType === 'percentage') {
            $discountPercentage = $discountValue;
        } elseif ($discountType === 'fixed') {
            $discountPercentage = ($discountValue / $this->harga) * 100;
        }

        // Calculate sample discounted price at min quantity
        $samplePrice = $this->harga;
        if ($minQuantity > 1) {
            // Calculate what the price would be at min quantity
            $pricing = $rule->buildPricingForProduct($this, $minQuantity);
            $samplePrice = $pricing['unit_price'] ?? $this->harga;
        } else {
            // Can apply at quantity 1
            if ($discountType === 'percentage') {
                $samplePrice = $this->harga * (1 - ($discountValue / 100));
            } elseif ($discountType === 'fixed') {
                $samplePrice = max(0, $this->harga - $discountValue);
            }
        }

        $samplePrice = max(0, $this->harga - $discountValue);
            }
        }

        return [
            'has_discount' => true,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_percentage' => round($discountPercentage, 2),
            'min_quantity' => $minQuantity,
            'base_price' => $this->harga,
                        'sample_discounted_price' => round($samplePrice, 0),
            'rule' => $rule,
        ];
    }
}
}
}
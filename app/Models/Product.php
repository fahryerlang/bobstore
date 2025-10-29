<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Get discount rules for this product
     */
    public function discountRules()
    {
        return $this->hasMany(\App\Models\DiscountRule::class);
    }

    /**
     * Get active discount rules
     */
    public function activeDiscountRules()
    {
        return $this->discountRules()
            ->whereHas('discount', function ($query) {
                $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                    });
            });
    }

    /**
     * Check if product has active discount (any quantity)
     */
    public function hasActiveDiscount()
    {
        return $this->activeDiscountRules()->exists();
    }

    /**
     * Get discount info for display (best discount available)
     */
    public function getDiscountDisplayInfo()
    {
        $rule = $this->activeDiscountRules()
            ->whereHas('discount', function ($query) {
                $query->where('applies_automatically', true);
            })
            ->orderBy('discount_value', 'desc')
            ->first();

        if (!$rule) {
            return null;
        }

        $minQty = $rule->min_quantity ?? 1;
        $type = $rule->discount_type;
        $value = $rule->discount_value;

        // Calculate prices
        $basePrice = $this->harga;
        $discountedPrice = $basePrice;

        if ($type === 'percentage') {
            $discountedPrice = $basePrice * (1 - ($value / 100));
            $percentage = $value;
        } else {
            $discountedPrice = max(0, $basePrice - $value);
            $percentage = ($value / $basePrice) * 100;
        }

        return [
            'has_discount' => true,
            'percentage' => round($percentage, 0),
            'base_price' => $basePrice,
            'discounted_price' => round($discountedPrice, 0),
            'min_quantity' => $minQty,
            'savings' => round($basePrice - $discountedPrice, 0),
        ];
    }
}

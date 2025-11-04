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
        'barcode',
        'nama_barang',
        'harga',
        'stok',
        'gambar',
        'images',
        'image_type',
        'category_id',
        'subcategory_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
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
     * Get the primary image URL (handles both external URLs and local paths)
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        // Check if it's an external URL
        if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
            return $this->gambar;
        }

        // It's a local file path
        return asset('storage/' . $this->gambar);
    }

    /**
     * Get all image URLs (handles both single and multiple images)
     */
    public function getAllImageUrlsAttribute(): array
    {
        $urls = [];

        // Handle image_type field
        if ($this->image_type === 'multiple' && !empty($this->images)) {
            foreach ($this->images as $image) {
                if (filter_var($image, FILTER_VALIDATE_URL)) {
                    $urls[] = $image;
                } else {
                    $urls[] = asset('storage/' . $image);
                }
            }
        } elseif ($this->image_type === 'url' && !empty($this->images)) {
            // Multiple URLs stored in images field
            foreach ($this->images as $image) {
                if (filter_var($image, FILTER_VALIDATE_URL)) {
                    $urls[] = $image;
                }
            }
        } elseif ($this->gambar) {
            // Single image in gambar field
            $urls[] = $this->image_url;
        }

        return array_filter($urls);
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
    public function getDiscountDisplayInfo($quantity = 1)
    {
        $basePrice = $this->harga;
        $baseTotal = $basePrice * $quantity;

        // Find applicable discount rule
        $rule = $this->activeDiscountRules()
            ->whereHas('discount', function ($query) {
                $query->where('applies_automatically', true);
            })
            ->where(function ($query) use ($quantity) {
                $query->where('min_quantity', '<=', $quantity)
                      ->orWhereNull('min_quantity');
            })
            ->orderBy('discount_value', 'desc')
            ->first();

        // No discount applicable
        if (!$rule) {
            return [
                'has_discount' => false,
                'discount_percentage' => 0,
                'base_unit_price' => $basePrice,
                'unit_price' => $basePrice,
                'base_total_price' => $baseTotal,
                'total_price' => $baseTotal,
                'total_discount' => 0,
                'min_quantity' => 1,
                'savings' => 0,
            ];
        }

        // Calculate discount
        $type = $rule->discount_type;
        $value = $rule->discount_value;
        $minQty = $rule->min_quantity ?? 1;

        if ($type === 'percentage') {
            $discountPerUnit = $basePrice * ($value / 100);
            $percentage = $value;
        } else {
            $discountPerUnit = $value;
            $percentage = ($value / $basePrice) * 100;
        }

        $unitPrice = max(0, $basePrice - $discountPerUnit);
        $totalPrice = $unitPrice * $quantity;
        $totalDiscount = ($basePrice - $unitPrice) * $quantity;

        return [
            'has_discount' => true,
            'discount_percentage' => round($percentage, 0),
            'base_unit_price' => $basePrice,
            'unit_price' => $unitPrice,
            'base_total_price' => $baseTotal,
            'total_price' => $totalPrice,
            'total_discount' => $totalDiscount,
            'min_quantity' => $minQty,
            'savings' => round($totalDiscount, 0),
        ];
    }
}

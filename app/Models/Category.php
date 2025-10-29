<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get subcategories for this category
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    /**
     * Get active subcategories
     */
    public function activeSubcategories(): HasMany
    {
        return $this->subcategories()->where('is_active', true);
    }

    /**
     * Get products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Discount rules scoped to this category.
     */
    public function discountRules(): HasMany
    {
        return $this->hasMany(DiscountRule::class);
    }

    /**
     * Scope for active categories only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

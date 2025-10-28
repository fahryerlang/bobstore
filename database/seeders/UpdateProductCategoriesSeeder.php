<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductTag;
use Illuminate\Database\Seeder;

class UpdateProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing products with categories and tags
        $products = Product::all();
        
        if ($products->count() > 0) {
            foreach ($products as $index => $product) {
                // Assign categories (rotate through available categories)
                $categoryId = ($index % 7) + 1; // 1-7
                $category = Category::find($categoryId);
                
                if ($category) {
                    $product->category_id = $categoryId;
                    
                    // Get a subcategory for this category
                    $subcategory = $category->subcategories()->first();
                    if ($subcategory) {
                        $product->subcategory_id = $subcategory->id;
                    }
                    
                    $product->save();
                    
                    // Attach random tags (1-3 tags per product)
                    $tagIds = ProductTag::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray();
                    $product->tags()->sync($tagIds);
                }
            }
            
            $this->command->info('Successfully updated ' . $products->count() . ' products with categories and tags!');
        } else {
            $this->command->warn('No products found to update.');
        }
    }
}

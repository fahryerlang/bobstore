<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscountRuleController extends Controller
{
    /**
     * Store a newly created rule for the given discount.
     */
    public function store(Request $request, Discount $discount): RedirectResponse
    {
        $data = $this->validateRule($request);

        $discount->rules()->create($data);

        return redirect()
            ->route('discounts.edit', $discount)
            ->with('success', 'Aturan diskon baru berhasil ditambahkan.');
    }

    /**
     * Update the specified discount rule.
     */
    public function update(Request $request, DiscountRule $discountRule): RedirectResponse
    {
        $data = $this->validateRule($request);

        $discountRule->update($data);

        return redirect()
            ->route('discounts.edit', $discountRule->discount_id)
            ->with('success', 'Aturan diskon berhasil diperbarui.');
    }

    /**
     * Remove the specified discount rule.
     */
    public function destroy(DiscountRule $discountRule): RedirectResponse
    {
        $discountId = $discountRule->discount_id;
        $discountRule->delete();

        return redirect()
            ->route('discounts.edit', $discountId)
            ->with('success', 'Aturan diskon berhasil dihapus.');
    }

    /**
     * Validate rule payload.
     */
    private function validateRule(Request $request): array
    {
        $validated = $request->validate([
            'target_type' => ['required', 'in:product,category,global'],
            'product_id' => ['nullable', 'exists:products,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'min_quantity' => ['nullable', 'integer', 'min:1'],
            'priority' => ['nullable', 'integer'],
        ]);

        $targetType = $validated['target_type'];

        if ($targetType === 'product') {
            $validated['product_id'] = $validated['product_id'] ?? null;
            if (! $validated['product_id']) {
                throw ValidationException::withMessages([
                    'product_id' => 'Produk wajib dipilih untuk aturan produk.',
                ]);
            }
            $validated['category_id'] = null;
        } elseif ($targetType === 'category') {
            $validated['category_id'] = $validated['category_id'] ?? null;
            if (! $validated['category_id']) {
                throw ValidationException::withMessages([
                    'category_id' => 'Kategori wajib dipilih untuk aturan kategori.',
                ]);
            }
            $validated['product_id'] = null;
        } else {
            $validated['product_id'] = null;
            $validated['category_id'] = null;
        }

        if ($validated['discount_type'] === DiscountRule::TYPE_PERCENTAGE) {
            $validated['discount_value'] = min(100, max(0, $validated['discount_value']));
        }

        $validated['min_quantity'] = $validated['min_quantity'] ?? 1;
        $validated['priority'] = $validated['priority'] ?? 0;

        unset($validated['target_type']);

        return $validated;
    }
}

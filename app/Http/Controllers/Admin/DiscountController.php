<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DiscountController extends Controller
{
    /**
     * Display a listing of the discounts.
     */
    public function index(): View
    {
        $discounts = Discount::withCount(['rules', 'coupons'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create(): View
    {
        return view('admin.discounts.create');
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDiscount($request);

        Discount::create($validated);

        return redirect()
            ->route('discounts.index')
            ->with('success', 'Diskon berhasil dibuat. Silakan tambahkan aturan atau kupon.');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount): View
    {
        $discount->load(['rules.product', 'rules.category', 'coupons']);

        $products = Product::orderBy('nama_barang')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.discounts.edit', compact('discount', 'products', 'categories'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $validated = $this->validateDiscount($request, $discount->id);

        $discount->update($validated);

        return redirect()
            ->route('discounts.edit', $discount)
            ->with('success', 'Data diskon berhasil diperbarui.');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()
            ->route('discounts.index')
            ->with('success', 'Diskon berhasil dihapus.');
    }

    /**
     * Validate discount payload.
     */
    private function validateDiscount(Request $request, ?int $discountId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100', Rule::unique('discounts', 'code')->ignore($discountId)],
            'description' => ['nullable', 'string'],
            'applies_automatically' => ['sometimes', 'boolean'],
            'is_stackable' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);
    }
}

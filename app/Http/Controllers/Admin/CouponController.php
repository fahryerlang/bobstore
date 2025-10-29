<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Store a newly created coupon for the given discount.
     */
    public function store(Request $request, Discount $discount): RedirectResponse
    {
        $data = $this->validateCoupon($request);

        $discount->coupons()->create($data);

        return redirect()
            ->route('discounts.edit', $discount)
            ->with('success', 'Kupon baru berhasil ditambahkan.');
    }

    /**
     * Update the specified coupon.
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $this->validateCoupon($request, $coupon->id);

        $coupon->update($data);

        return redirect()
            ->route('discounts.edit', $coupon->discount_id)
            ->with('success', 'Data kupon berhasil diperbarui.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $discountId = $coupon->discount_id;
        $coupon->delete();

        return redirect()
            ->route('discounts.edit', $discountId)
            ->with('success', 'Kupon berhasil dihapus.');
    }

    /**
     * Validate coupon payload.
     */
    private function validateCoupon(Request $request, ?int $couponId = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:100', Rule::unique('coupons', 'code')->ignore($couponId)],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_customer_limit' => ['nullable', 'integer', 'min:1'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['usage_limit'] = $data['usage_limit'] ?? null;
        $data['per_customer_limit'] = $data['per_customer_limit'] ?? null;
        $data['min_order_value'] = $data['min_order_value'] ?? 0;

        return $data;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'cashier_id',
        'customer_id',
        'subtotal',
        'discount',
        'total',
        'amount_paid',
        'change_due',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_due' => 'decimal:2',
    ];

    /**
     * Get the cashier who processed the transaction.
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the customer associated with the transaction.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the sale items belonging to this transaction.
     */
    public function items()
    {
        return $this->hasMany(Sale::class, 'sale_transaction_id');
    }

    /**
     * Use invoice number for implicit route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'invoice_number';
    }
}

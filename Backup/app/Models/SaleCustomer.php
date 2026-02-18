<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleCustomer extends Model
{
    protected $fillable = [
        'sale_id',
        'customer_id',
        'quantity',
        'price',
        'payment_method',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    /**
     * Get the sale that owns the sale customer.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the customer that owns the sale customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

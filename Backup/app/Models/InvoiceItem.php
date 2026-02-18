<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_id',
        'quantity',
        'cost_price',
        'total',
        'vat_amount',
    ];
    
    protected $casts = [
        'quantity' => 'integer',
        'cost_price' => 'decimal:2',
        'total' => 'decimal:2',
        'vat_amount' => 'decimal:2',
    ];
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    /**
     * Format cost price with thousand separators
     */
    public function formatCostPrice()
    {
        return number_format($this->cost_price, 2, '.', ',');
    }
    
    /**
     * Format total with thousand separators
     */
    public function formatTotal()
    {
        return number_format($this->total, 2, '.', ',');
    }
}

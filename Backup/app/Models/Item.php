<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Item extends Model
{
    protected $fillable = [
        'item_code',
        'name',
        'category_id',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'status',
        'vat_available',
    ];
    
    protected $casts = [
        'cost_price' => 'decimal:5',
        'selling_price' => 'decimal:2',
        'stock_quantity' => 'decimal:2',
        'status' => 'string',
        'vat_available' => 'boolean',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
    
    public static function rules($id = null)
    {
        return [
            'item_code' => ['required', 'string', Rule::unique('items', 'item_code')->ignore($id)],
            'name' => ['required', 'string', Rule::unique('items', 'name')->ignore($id)],
            'category_id' => ['required', 'exists:categories,id'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public static function generateItemCode()
    {
        $lastItem = self::latest('id')->first();
        $number = $lastItem ? intval(substr($lastItem->item_code, 4)) + 1 : 1;
        return 'ITM-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Format cost price with thousand separators
     */
    public function formatCostPrice()
    {
        return number_format($this->cost_price, 2, '.', ',');
    }
    
    /**
     * Format selling price with thousand separators
     */
    public function formatSellingPrice()
    {
        return number_format($this->selling_price, 2, '.', ',');
    }
}

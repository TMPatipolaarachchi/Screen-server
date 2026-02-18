<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Tank extends Model
{
    protected $fillable = [
        'item_id',
        'tank_name',
        'max_stock',
        'status',
    ];
    
    protected $attributes = [
        'status' => 'active',
    ];
    
    protected $casts = [
        'max_stock' => 'decimal:2',
        'status' => 'string',
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class);
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
            'item_id' => ['required', 'exists:items,id'],
            'tank_name' => ['required', 'string', 'max:255', Rule::unique('tanks', 'tank_name')->ignore($id)],
            'max_stock' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:active,inactive'],
        ];
    }
    
    public static function createRules()
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'tank_name' => ['required', 'string', 'max:255', 'unique:tanks,tank_name'],
            'max_stock' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:active,inactive'],
        ];
    }
    
    public static function updateRules($id)
    {
        return self::rules($id);
    }
}

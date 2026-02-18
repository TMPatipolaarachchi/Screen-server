<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Meter extends Model
{
    protected $fillable = [
        'meter_name',
        'item_id',
        'meter_value',
        'status',
    ];
    
    protected $attributes = [
        'status' => 'active',
    ];
    
    protected $casts = [
        'meter_value' => 'decimal:3', // 3 decimal points as requested
        'status' => 'string',
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    public function pumps()
    {
        return $this->hasMany(Pump::class);
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
            'meter_name' => ['required', 'string', Rule::unique('meters', 'meter_name')->ignore($id)],
            'item_id' => ['required', 'exists:items,id'],
            'meter_value' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:active,inactive'],
        ];
    }
    
    public static function createRules()
    {
        return [
            'meter_name' => ['required', 'string', 'unique:meters,meter_name'],
            'item_id' => ['required', 'exists:items,id'],
            'meter_value' => ['required', 'numeric', 'min:0'],
        ];
    }
    
    public static function updateRules($id)
    {
        return self::rules($id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Pump extends Model
{
    protected $fillable = [
        'name',
        'tank_id',
        'meter1_id',
        'meter2_id',
        'status',
    ];
    
    protected $attributes = [
        'status' => 'active',
    ];
    
    protected $casts = [
        'status' => 'string',
    ];
    
    public function tank()
    {
        return $this->belongsTo(Tank::class, 'tank_id');
    }
    
    public function meter1()
    {
        return $this->belongsTo(Meter::class, 'meter1_id');
    }
    
    public function meter2()
    {
        return $this->belongsTo(Meter::class, 'meter2_id');
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
            'name' => ['required', 'string', Rule::unique('pumps', 'name')->ignore($id)],
            'tank_id' => ['required', 'exists:tanks,id'],
            'meter1_id' => ['required', 'exists:meters,id'],
            'meter2_id' => ['nullable', 'exists:meters,id', 'different:meter1_id'],
            'status' => ['sometimes', 'in:active,inactive'],
        ];
    }
    
    public static function createRules()
    {
        return [
            'name' => ['required', 'string', 'unique:pumps,name'],
            'meter1_id' => ['required', 'exists:meters,id'],
            'meter2_id' => ['nullable', 'exists:meters,id', 'different:meter1_id'],
        ];
    }
    
    public static function updateRules($id)
    {
        return self::rules($id);
    }
}

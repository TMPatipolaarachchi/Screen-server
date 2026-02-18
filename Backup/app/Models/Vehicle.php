<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_code',
        'vehicle_number',
        'status',
    ];
    
    protected $casts = [
        'status' => 'string',
    ];
    
    /**
     * Scope to filter active vehicles
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    /**
     * Generate a unique vehicle code
     */
    public static function generateVehicleCode()
    {
        $lastVehicle = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastVehicle ? intval(substr($lastVehicle->vehicle_code, 3)) : 0;
        $newNumber = $lastNumber + 1;
        return 'VEH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
    
    /**
     * Validation rules
     */
    public static function rules($id = null)
    {
        return [
            'vehicle_number' => ['required', 'string', 'max:50', 'unique:vehicles,vehicle_number,' . $id],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}

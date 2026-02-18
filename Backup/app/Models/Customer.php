<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'status',
    ];

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get validation rules for customer.
     */
    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:customers,phone_number' . ($id ? ",$id" : ''),
            'address' => 'required|string',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}

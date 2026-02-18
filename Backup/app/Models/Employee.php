<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'nic',
        'etf_number',
        'birthday',
        'address',
        'role',
        'status',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    /**
     * Scope a query to only include active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get validation rules for employee.
     */
    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'nic' => 'required|string|max:20|unique:employees,nic' . ($id ? ",$id" : ''),
            'etf_number' => 'required|string|max:50|unique:employees,etf_number' . ($id ? ",$id" : ''),
            'birthday' => 'required|date|before:today',
            'address' => 'required|string',
            'role' => 'required|in:pumper,driver,helper',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}

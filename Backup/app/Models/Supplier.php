<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'bank_name',
        'account_number',
        'status',
    ];
    
    protected $casts = [
        'status' => 'string',
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
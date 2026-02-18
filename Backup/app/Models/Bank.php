<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Bank extends Model
{
    protected $fillable = [
        'name',
        'account',
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
    
    public static function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('banks', 'name')],
            'account' => ['required', 'string', Rule::unique('banks', 'account')],
        ];
    }
}

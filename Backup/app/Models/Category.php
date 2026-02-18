<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Category extends Model
{
    protected $fillable = [
        'name',
        'code',
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
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    
    public static function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('categories', 'name')],
            'code' => ['required', 'string', Rule::unique('categories', 'code')],
        ];
    }

    public static function generateCode()
    {
        $lastCategory = self::latest('id')->first();
        $number = $lastCategory ? intval(substr($lastCategory->code, 4)) + 1 : 1;
        return 'CAT-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}

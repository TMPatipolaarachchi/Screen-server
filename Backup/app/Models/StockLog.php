<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = [
        'item_id',
        'tank_id',
        'type',
        'before_quantity',
        'quantity_change',
        'after_quantity',
        'reason',
        'reference_type',
        'reference_id',
        'user_id',
    ];

    protected $casts = [
        'before_quantity' => 'decimal:2',
        'quantity_change' => 'decimal:2',
        'after_quantity' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierBalanceLog extends Model
{
    protected $fillable = [
        'supplier_id',
        'type',
        'amount',
        'previous_balance',
        'current_balance',
        'reference_type',
        'reference_id',
        'description',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

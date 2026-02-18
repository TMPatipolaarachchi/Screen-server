<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_date',
        'payment_method',
        'supplier_id',
        'amount',
        'remaining_balance',
        'status',
        'bank_id',
        'reference_number',
        'cheque_number',
    ];
    
    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'payment_method' => 'string',
    ];
    

    
    /**
     * Check if payment can be edited
     */
    public function canEdit()
    {
        // Can edit if status is pending and remaining balance >= 1
        return $this->status === 'pending' && $this->remaining_balance >= 1;
    }
    
    /**
     * Check if payment can be deleted
     */
    public function canDelete()
    {
        // Can delete only if it's pending and not used in setoffs
        return $this->status === 'pending' && !$this->setoffs()->exists();
    }
    
    /**
     * Scope a query to only include payments with specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include complete payments.
     */
    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }
    
    /**
     * Scope a query to only include deleted payments.
     */
    public function scopeDeleted($query)
    {
        return $query->where('status', 'deleted');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    
    public function setoffs()
    {
        return $this->hasMany(InvoicePaymentSetoff::class);
    }
    
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_payment_setoffs')
                    ->withPivot('amount')
                    ->withTimestamps();
    }
    
    public function getUsedAmountAttribute()
    {
        return round($this->setoffs()->sum('amount'), 2);
    }
    
    public function getRemainingAmountAttribute()
    {
        return round($this->amount - $this->used_amount, 2);
    }
    
    public static function rules($id = null)
    {
        return [
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:online,cheque'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'bank_id' => ['required_if:payment_method,online', 'nullable', 'exists:banks,id'],
            'reference_number' => ['required_if:payment_method,online', 'nullable', 'string', 'max:255'],
            'cheque_number' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:255'],
        ];
    }
    
    /**
     * Format currency with thousand separators
     */
    public function formatCurrency($value = null)
    {
        $amount = $value ?? $this->amount;
        return number_format($amount, 2, '.', ',');
    }
    
    /**
     * Format remaining balance with thousand separators
     */
    public function formatRemainingBalance()
    {
        return number_format($this->remaining_balance, 2, '.', ',');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'supplier_id',
        'vehicle_id',
        'employee_id',
        'helper_id',
        'subtotal',
        'vat_percentage',
        'vat_amount',
        'bank_charge',
        'total_amount',
        'status',
    ];
    
    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'bank_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'status' => 'string',
    ];
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function helper()
    {
        return $this->belongsTo(Employee::class, 'helper_id');
    }
    
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    public function paymentSetoffs()
    {
        return $this->hasMany(InvoicePaymentSetoff::class);
    }
    
    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'invoice_payment_setoffs')
                    ->withPivot('amount')
                    ->withTimestamps();
    }
    
    public function getPaidAmountAttribute()
    {
        return $this->paymentSetoffs()->sum('amount');
    }
    
    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
    
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    
    public static function rules($id = null)
    {
        return [
            'invoice_number' => ['required', 'string', Rule::unique('invoices', 'invoice_number')->ignore($id)],
            'invoice_date' => ['required', 'date'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
        ];
    }
    
    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::latest('id')->first();
        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Format currency with thousand separators
     */
    public function formatCurrency($value = null)
    {
        $amount = $value ?? $this->total_amount;
        return number_format($amount, 2, '.', ',');
    }
    
    /**
     * Format subtotal with thousand separators
     */
    public function formatSubtotal()
    {
        return number_format($this->subtotal, 2, '.', ',');
    }
    
    /**
     * Format VAT amount with thousand separators
     */
    public function formatVatAmount()
    {
        return number_format($this->vat_amount, 2, '.', ',');
    }
    
    /**
     * Format bank charge with thousand separators
     */
    public function formatBankCharge()
    {
        return number_format($this->bank_charge, 2, '.', ',');
    }
    
    /**
     * Format total amount with thousand separators
     */
    public function formatTotalAmount()
    {
        return number_format($this->total_amount, 2, '.', ',');
    }
    
    /**
     * Format paid amount with thousand separators
     */
    public function formatPaidAmount()
    {
        return number_format($this->paid_amount, 2, '.', ',');
    }
    
    /**
     * Format remaining amount with thousand separators
     */
    public function formatRemainingAmount()
    {
        return number_format($this->remaining_amount, 2, '.', ',');
    }
}

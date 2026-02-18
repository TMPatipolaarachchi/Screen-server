<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'meter_id',
        'pump_id',
        'employee_id',
        'customer_id',
        'last_meter_value',
        'completion_meter_value',
        'total_selling_price',
        'status',
    ];

    protected $casts = [
        'last_meter_value' => 'decimal:2',
        'completion_meter_value' => 'decimal:2',
        'total_selling_price' => 'decimal:2',
    ];

    /**
     * Get the meter that owns the sale.
     */
    public function meter()
    {
        return $this->belongsTo(Meter::class);
    }

    /**
     * Get the pump that owns the sale.
     */
    public function pump()
    {
        return $this->belongsTo(Pump::class);
    }

    /**
     * Get the employee (pumper) that owns the sale.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the customer that owns the sale.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all customers associated with this sale.
     */
    public function saleCustomers()
    {
        return $this->hasMany(SaleCustomer::class);
    }

    /**
     * Get validation rules for sale.
     */
    public static function rules($id = null)
    {
        return [
            'meter_id' => 'required|exists:meters,id',
            'pump_id' => 'required|exists:pumps,id',
            'employee_id' => 'required|exists:employees,id',
            'customer_id' => 'nullable|exists:customers,id',
            'last_meter_value' => 'required|numeric|min:0',
            'completion_meter_value' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,complete',
        ];
    }
}

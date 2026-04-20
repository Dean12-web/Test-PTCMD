<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    protected $fillable = [
        'customer_id',
        'application_type',
        'nominal',
        'tenor',
        'monthly_installment',
        'notes',
        'status',
        'filling_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

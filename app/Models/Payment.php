<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rental_id',
        'payment_number',
        'amount',
        'type',
        'method',
        'status',
        'payment_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the rental that owns the payment.
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Scope a query to only include completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include deposit payments.
     */
    public function scopeDeposit($query)
    {
        return $query->where('type', 'deposit');
    }

    /**
     * Scope a query to only include rental payments.
     */
    public function scopeRental($query)
    {
        return $query->where('type', 'rental');
    }
}
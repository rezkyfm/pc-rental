<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'user_id',
        'pc_id',
        'start_time',
        'end_time',
        'actual_return_time',
        'total_price',
        'deposit_amount',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'actual_return_time' => 'datetime',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the rental.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the PC that is being rented.
     */
    public function pc()
    {
        return $this->belongsTo(PC::class, 'pc_id'); // Explicitly specify the foreign key
    }

    /**
     * Get the payments for this rental.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope a query to only include active rentals.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed rentals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Calculate rental duration in hours.
     */
    public function getDurationInHoursAttribute()
    {
        $endTime = $this->actual_return_time ?? $this->end_time ?? now();
        return $this->start_time->diffInHours($endTime);
    }

    /**
     * Calculate if the rental is overdue.
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status !== 'active') {
            return false;
        }

        return now()->gt($this->end_time);
    }
}
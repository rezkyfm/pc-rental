<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maintenance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pc_id',
        'title',
        'description',
        'type',
        'scheduled_date',
        'completed_date',
        'status',
        'cost',
        'performed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_date' => 'datetime',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the PC that owns the maintenance.
     */
    public function pc()
    {
        return $this->belongsTo(PC::class);
    }

    /**
     * Get the user that performed the maintenance.
     */
    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Scope a query to only include scheduled maintenance.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include completed maintenance.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include in progress maintenance.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
}
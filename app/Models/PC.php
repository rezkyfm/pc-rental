<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PC extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pcs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'rental_price_hourly',
        'rental_price_daily',
        'status',
        'assembly_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rental_price_hourly' => 'decimal:2',
        'rental_price_daily' => 'decimal:2',
        'assembly_date' => 'date',
    ];

    /**
     * Get the components for this PC.
     */
    public function components()
    {
        return $this->belongsToMany(Component::class, 'pc_components', 'pc_id', 'component_id')
            ->withPivot('installation_date', 'notes')
            ->withTimestamps();
    }

    /**
     * Get the rentals for this PC.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'pc_id'); // Explicitly specify the foreign key
    }

    /**
     * Get the maintenance records for this PC.
     */
    public function maintenanceRecords()
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Scope a query to only include available PCs.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include rented PCs.
     */
    public function scopeRented($query)
    {
        return $query->where('status', 'rented');
    }

    /**
     * Check if PC is available for rent.
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
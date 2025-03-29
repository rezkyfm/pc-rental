<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'model',
        'specifications',
        'purchase_price',
        'purchase_date',
        'serial_number',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    /**
     * Get the category that owns the component.
     */
    public function category()
    {
        return $this->belongsTo(ComponentCategory::class, 'category_id');
    }

    /**
     * Get the PCs that use this component.
     */
    public function pcs()
    {
        return $this->belongsToMany(PC::class, 'pc_components', 'component_id', 'pc_id')
            ->withPivot('installation_date', 'notes')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include available components.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include components in use.
     */
    public function scopeInUse($query)
    {
        return $query->where('status', 'in_use');
    }
}
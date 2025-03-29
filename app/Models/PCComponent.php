<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PCComponent extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pc_components';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pc_id',
        'component_id',
        'installation_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'installation_date' => 'date',
    ];

    /**
     * Get the PC that owns the component.
     */
    public function pc()
    {
        return $this->belongsTo(PC::class);
    }

    /**
     * Get the component for this PC.
     */
    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
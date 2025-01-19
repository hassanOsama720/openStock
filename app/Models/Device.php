<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Device extends Model
{
    protected $table = 'devices';

    protected $fillable = [
        'device_type',
        'device_id',
        'mac_address',
        'devicable_id',
        'devicable_type',
        'preferred_locale',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    /**
     * Get the owning devicable model.
     */
    public function devicable(): MorphTo
    {
        return $this->morphTo();
    }
}

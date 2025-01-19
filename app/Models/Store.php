<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Store extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'name',
        'user_id',
        'commercial_register',
        'currency',
    ];


    // Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(StoreAddress::class);
    }
}

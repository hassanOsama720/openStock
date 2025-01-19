<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends AuthBaseModel
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'email_verified_at',
        'sub_domain',
        'last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function otps(): MorphMany
    {
        return $this->morphMany(AuthOtp::class, 'otpable');
    }

    /**
     * Get all the user's devices.
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(Device::class, 'devicable');
    }

    /**
     * Get user's current device.
     */
    public function currentDevice(): MorphOne
    {
        return $this->morphOne(Device::class, 'devicable')->where('is_current', true);
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

}

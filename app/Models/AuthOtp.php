<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuthOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'country_code',
        'type',
        'code',
        'otpable_id',
        'otpable_type',
        'expired_at',
    ];


    /**
     * Get the parent atpable model.
     */
    public function otpable(): MorphTo
    {
        return $this->morphTo();
    }


    /**
     * get the identifier type
     * @return string
     */
    public function getIdentifierTypeAttribute() : string
    {
        return isEmail($this->identifier) ? 'email' : 'phone';
    }


    /**
     * Determine whither the verification code is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return false;
        //TODO: uncomment this line
        //return $this->updated_at->addMinutes(1)->isPast();
    }


    public static function boot(): void
    {
        parent::boot();
        self::creating(function ($model) {
            $model->expired_at = Carbon::now()->addMinute();
        });
    }
}

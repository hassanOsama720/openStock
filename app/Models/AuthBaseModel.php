<?php

namespace App\Models;

use App\Models\Helper\AuthBaseHelper;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthBaseModel extends Authenticatable
{

    use Notifiable,
        UploadTrait,
        HasApiTokens,
        AuthBaseHelper,
        SoftDeletes;


    protected $hidden = [
        'password',
    ];



    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        /* creating, created, updating, updated, deleting, deleted, forceDeleted, restored */

        static::created(function ($model) {
            if (request()->device_id && request()->device_type) {
                // create a new device for user
                $model->devices()->create([
                    'device_id'        => request()->device_id,
                    'device_type'      => request()->device_type,
                    'mac_address'      => request()->mac_address,
                    'preferred_locale' => request()->header('Accept-Language'),
                    'is_current'       => true,
                ]);
            }
        });
    }

}

<?php

namespace App\Enums;

/**
 * Class BannerType
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class OTPType extends Base
{
    public const VERIFICATION    = 'verification';
    public const LOGIN           = 'login';
    public const FORGET_PASSWORD = 'forget_password';
    public const PHONE_OWNERSHIP    = 'phone_ownership';
    public const UPDATE_PHONE    = 'update_phone';
    public const UPDATE_EMAIL    = 'update_email';
}

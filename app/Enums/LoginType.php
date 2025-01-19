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
class LoginType extends Base
{
    public const NORMAL = 'normal';
    public const CODE = 'code';
}

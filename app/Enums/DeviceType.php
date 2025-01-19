<?php

namespace App\Enums;

/**
 * Class OrderPayType
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class DeviceType extends Base {
    public const WEB     = 'web';
    public const IOS     = 'ios';
    public const ANDROID = 'android';
}

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
class StoreActivityType extends Base {
    public const Retal = 'retal';
    public const Sale = 'sale';
    public const Manufacture = 'manufacture';

}

<?php

namespace App\Enums;

enum ContactType: string
{
    case COMPLAINT = 'complaint';
    case SUGGESTION = 'suggestion';

    public static function getRandomValue(): self
    {
        $cases = self::cases();
        return $cases[array_rand($cases)];
    }
}

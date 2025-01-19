<?php

namespace App\Enums;

enum OTPThrough: string
{
    case PHONE = 'phone';
    case EMAIL = 'email';
}

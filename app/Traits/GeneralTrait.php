<?php

namespace App\Traits;

trait GeneralTrait
{
    public function isCodeCorrect($code, $type, $entity = null): bool
    {
        return $entity?->otps()->where(['code' => $code, 'type' => $type])->exists();
    }

    public function isCodeExpired($code, $type, $entity = null): bool
    {
        $otp = $entity->otps()->where(['code' => $code, 'type' => $type])->first();
        return optional($otp)->isExpired() ? true : false;
    }
}

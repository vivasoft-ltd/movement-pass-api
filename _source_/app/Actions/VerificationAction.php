<?php


namespace App\Actions;


use App\DTO\PhoneVerificationDTO;

class VerificationAction
{
    public function __invoke(PhoneVerificationDTO $phoneVerificationDTO): bool
    {
        if ($phoneVerificationDTO->user->code == $phoneVerificationDTO->code) {
            return $phoneVerificationDTO->user->update([
                'code'      => null,
                'active'    => true,
            ]);
        }

        return false;
    }
}

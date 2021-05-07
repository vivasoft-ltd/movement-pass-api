<?php


namespace App\Actions;


use App\DTO\AuthenticationDTO;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function __invoke(AuthenticationDTO $dto): bool|string
    {
        if ($token = Auth::attempt($dto->credentials()) ) {
            return $token;
        }

        return false;
    }
}

<?php


namespace App\Actions;


use App\DTO\AuthenticationDTO;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function __invoke(AuthenticationDTO $dto): bool|string
    {
        $cred = $dto->getLoginCredential();

        if (Auth::validate($cred) ) {
            return Auth::attempt($cred);
        }

        return false;
    }
}

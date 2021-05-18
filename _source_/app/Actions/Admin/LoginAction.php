<?php


namespace App\Actions\Admin;


use App\DTO\AuthenticationDTO;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function __invoke(AuthenticationDTO $dto): bool|string
    {
        $cred = $dto->getLoginCredential();

        if (Auth::guard('admin')->validate($cred) ) {
            return Auth::guard('admin')->attempt($cred);
        }

        return false;
    }
}

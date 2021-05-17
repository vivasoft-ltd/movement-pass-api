<?php


namespace App\DTO;


use Illuminate\Http\Request;
use ReflectionException;

class AuthenticationDTO extends DTO
{
    public string $phone;

    public string $password;

    public bool $allowLoginOnlyForActiveUser = true;

    /**
     * @throws ReflectionException
     */
    public static function createFromRequest(Request $request): self
    {
        return static::self([
            'phone'     => $request->input('phone'),
            'password'  => $request->input('password'),
        ]);
    }

    public function getLoginCredential(): array
    {
        return [
            'phone'     => $this->phone,
            'password'  => $this->password,
            'active'    => $this->allowLoginOnlyForActiveUser,
        ];
    }
}

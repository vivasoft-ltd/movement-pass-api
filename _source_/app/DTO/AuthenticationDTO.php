<?php


namespace App\DTO;


use Illuminate\Http\Request;

class AuthenticationDTO
{
    public string $email;

    public string $password;

    public static function createFromRequest(Request $request): self
    {
        $self = new self();

        $self->email = $request->input('email');
        $self->password = $request->input('password');

        return $self;
    }

    public function credentials(): array
    {
        return [
            'email'     => $this->email,
            'password'  => $this->password,
        ];
    }
}

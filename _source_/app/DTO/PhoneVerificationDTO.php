<?php


namespace App\DTO;


use App\Models\User;
use Illuminate\Http\Request;

class PhoneVerificationDTO extends DTO
{
    public User $user;

    public string $code;

    public static function createFromRequest(Request $request): static
    {
        return static::self([
            'user' => User::where('phone', $request->input('phone'))->firstOrFail(),
            'code' => $request->input('code'),
        ]);
    }
}

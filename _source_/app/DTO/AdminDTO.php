<?php


namespace App\DTO;


use App\DataTypes\AdminRole;
use App\DataTypes\Gender;
use App\DataTypes\IdCards as IdCardType;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminDTO extends DTO
{
    public string $name;

    public string $phone;

    public string $password;

    public UploadedFile $image;

    public AdminRole $role;

    public bool $active;

    public static function createFromRequest(Request $request): static
    {
        return static::self([
            'name'      => $request->input('name'),
            'phone'     => $request->input('phone'),
            'password'  => Hash::make($request->input('password'), [ 'rounds' => 8 ]),
            'image'     => $request->file('image'),
            'active'    => true,
            'role'      => AdminRole::make( $request->input('role') ),
        ]);
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $data['role'] = $this->role->value();

        return $data;
    }
}

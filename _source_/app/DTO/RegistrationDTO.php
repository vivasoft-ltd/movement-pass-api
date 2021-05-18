<?php

namespace App\DTO;


use App\DataTypes\Gender;
use App\DataTypes\IdCards as IdCardType;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use SwooleTW\Http\Helpers\Dumper;

class RegistrationDTO extends DTO
{
    public string $name;

    public string $phone;

    public string $password;

    public string $district;

    public string $upaZilla;

    public Gender $gender;

    public Carbon $birthdate;

    public IdCardType $cardType;

    public string $cardNumber;

    public UploadedFile $image;

    public bool $active;

    public static function createFromRequest(Request $request): static
    {
        return static::self([
            'name'      => $request->input('name'),
            'phone'     => $request->input('phone'),
            'password'  => Hash::make($request->input('password'), [ 'rounds' => 8 ]),
            'district'  => $request->input('district'),
            'upaZilla'  => $request->input('upaZilla'),
            'gender'    => Gender::make($request->input('gender')),
            'birthdate' => Carbon::parse($request->input('birthdate')),
            'cardType'  => IdCardType::make($request->input('cardType')),
            'cardNumber'=> $request->input('cardNumber'),
            'image'     => $request->file('image'),
            'active'    => false,
        ]);
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $data['gender'] = $this->gender->value();
        $data['cardType'] = $this->cardType->value();
        $data['birthdate'] = $this->birthdate->format('Y-m-d');

        return $data;
    }
}

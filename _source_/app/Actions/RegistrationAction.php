<?php


namespace App\Actions;


use App\DTO\RegistrationDTO;
use App\Events\Registered;
use App\Models\User;
use Illuminate\Support\Arr;
use SwooleTW\Http\Helpers\Dumper;

class RegistrationAction
{
    public function __invoke(RegistrationDTO $registrationData)
    {
        $data = $registrationData->toArray();

        $data['image'] = (new UploadAction)(Arr::pull($data, 'image'), 'users');

        $user = User::create($data);

        event(new Registered($user));

        return $user;
    }
}

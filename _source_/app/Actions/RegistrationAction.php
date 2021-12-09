<?php


namespace App\Actions;


use App\DTO\RegistrationDTO;
use App\Events\Registered;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionException;

class RegistrationAction
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(RegistrationDTO $registrationData)
    {
        $output = [];

        $data = $registrationData->toArray();

        if ($data['image']) {
            $data['image'] = (new UploadAction)(
                Arr::pull($data, 'image'),
                'users', [
                    'visibility' => 'public'
                ]
            );
        } else {
            [
                'file' => $data['image'],
                'signedUrl' => $output['signedUrl'],
            ] = (new UploadAction)->createS3SignedUrl(
                'users/' . Str::random()
            );
        }

        $user = User::create($data);

        event(new Registered($user));

        return array_merge($output, $user->toArray());
    }
}

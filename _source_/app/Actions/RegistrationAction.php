<?php


namespace App\Actions;


use App\DTO\RegistrationDTO;
use App\Events\Registered;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
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

    public function confirmImageUpload($phone): bool
    {
        if ($user = User::where('phone', $phone)->first()) {
            return (new UploadAction)->makeS3FileVisibilityPublic($user->image);
        }

        return false;
    }
}

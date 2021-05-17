<?php


namespace App\Actions;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadAction
{
    public function __invoke(UploadedFile $file, $location): bool|string
    {
        return Storage::putFile($location, $file);
    }
}

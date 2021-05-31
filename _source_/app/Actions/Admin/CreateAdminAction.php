<?php


namespace App\Actions\Admin;


use App\Actions\UploadAction;
use App\DTO\AdminDTO;
use App\Models\Admin;
use Illuminate\Support\Arr;

class CreateAdminAction
{
    public function __invoke(AdminDTO $adminDTO)
    {
        $data = $adminDTO->toArray();

        $data['image'] = (new UploadAction)(Arr::pull($data, 'image'), 'admin');

        return Admin::create($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function getAdminUser(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard('admin')->user();
    }
}

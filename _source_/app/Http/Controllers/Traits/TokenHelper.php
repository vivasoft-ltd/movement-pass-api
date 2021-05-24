<?php
namespace App\Http\Controllers\Traits;

use App\Models\AccessToken;
use Illuminate\Http\Request;

trait TokenHelper
{
    protected function deleteCurrentToken(Request $request)
    {
        $decodedToken = app('tymon.jwt.provider.jwt')->decode($request->bearerToken());
        $token = AccessToken::where(['jti' => $decodedToken['jti']])->get()->first();
        $token->delete();
    }
}

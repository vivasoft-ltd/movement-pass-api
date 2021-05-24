<?php
namespace App\Http\Controllers\Admin;

use App\Actions\Admin\LoginAction as AdminLoginAction;
use App\DTO\AuthenticationDTO;
use App\Events\LoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TokenHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use TokenHelper;
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($this->getAdminUser());
    }

    public function login(Request $request, AdminLoginAction $loginAction)
    {
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required'
        ]);

        if ($token = $loginAction(AuthenticationDTO::createFromRequest($request))) {
            event(new LoggedIn($this->getAdminUser(), $token));
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ]);
        }

        return response()->json(['message' => 'The credentials do not match our records!'], 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->deleteCurrentToken($request);
        return response()->json(['message' => 'Logged out successfully']);
    }
}

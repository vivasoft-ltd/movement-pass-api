<?php
namespace App\Http\Controllers\Admin;

use App\Actions\Admin\LoginAction as AdminLoginAction;
use App\DTO\AuthenticationDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ]);
        }

        return response()->json(['message' => 'The credentials do not match our records!'], 401);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'token' => auth('admin')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

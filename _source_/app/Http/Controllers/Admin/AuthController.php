<?php
namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateAdminAction;
use App\Actions\Admin\LoginAction as AdminLoginAction;
use App\DataTypes\AdminRole;
use App\DTO\AdminDTO;
use App\DTO\AuthenticationDTO;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class, [
            'only' => 'register',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $this->getAdminUser()
        ]);
    }

    public function register(Request $request, CreateAdminAction $createAdminAction)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
            'phone' => ['required', 'regex:/^(?:\+88|01)?(?:\d{11}|\d{13})$/', Rule::unique('admins', 'phone')],
            'image' => ['required', 'image', 'max:800'],
            'role'  => ['required', Rule::in(AdminRole::toArray())]
        ]);

        return response()->json(
            $createAdminAction(AdminDTO::createFromRequest($request))
        );
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

<?php

namespace App\Http\Controllers;


use App\Actions\LoginAction;
use App\Models\User;
use App\DTO\AuthenticationDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Register user
     *
     * @param Request $request
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json($user);
    }

    /**
     * Login
     *
     * @param Request $request
     */
    public function login(Request $request, LoginAction $loginAction)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ( $token = $loginAction(AuthenticationDTO::createFromRequest($request)) ) {
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
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}

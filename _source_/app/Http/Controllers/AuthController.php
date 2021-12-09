<?php

namespace App\Http\Controllers;


use App\Actions\LoginAction;
use App\Actions\RegistrationAction;
use App\Actions\UploadAction;
use App\Actions\VerificationAction;
use App\DataTypes\Gender;
use App\DataTypes\IdCards;
use App\DTO\PhoneVerificationDTO;
use App\DTO\RegistrationDTO;
use App\DTO\AuthenticationDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use ReflectionException;

class AuthController extends Controller
{
    /**
     * Register user
     *
     * @param Request $request
     * @param RegistrationAction $registrationAction
     * @return JsonResponse
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function register(Request $request, RegistrationAction $registrationAction): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
            'phone' => ['required', 'regex:/^(?:\+88|01)?(?:\d{11}|\d{13})$/', Rule::unique('users', 'phone')],
            'district' => 'required',
            'upaZilla' => 'required',
            'gender' => ['required', Rule::in(Gender::toArray())],
            'birthdate' => ['required', 'date'],
            'cardType' => ['required', Rule::in(IdCards::toArray())],
            'cardNumber' => 'required',
            'image' => ['image', 'max:800'],
        ]);

        $user = $registrationAction(RegistrationDTO::createFromRequest($request));

        return response()->json($user);
    }

    /**
     * @throws ValidationException
     */
    public function confirmImageUpload(Request $request, RegistrationAction $action): JsonResponse
    {
        $this->validate($request, [
            'phone' => ['required', 'regex:/^(?:\+88|01)?(?:\d{11}|\d{13})$/', Rule::exists('users', 'phone')]
        ]);

        if ($action->confirmImageUpload($request->phone)) {
            return response()->json([
                'message' => 'Image visibility changed to public'
            ]);
        }

        return response()->json([
            'message' => 'Something is wrong happen, please try again leter.'
        ], 400);
    }

    /**
     * @throws ValidationException
     */
    public function verify(Request $request, VerificationAction $verificationAction): JsonResponse
    {
        $this->validate($request, [
            'phone' => ['required', 'regex:/^(?:\+88|01)?(?:\d{11}|\d{13})$/', Rule::exists('users', 'phone')],
            'code'  => ['required'],
        ]);

        if ( $verificationAction( PhoneVerificationDTO::createFromRequest( $request ) ) ) {
            return response()->json([
                'message' => 'Your phone number has been verified successfully.'
            ]);
        }

        return response()->json([
            'message' => 'Opps! We could not verify your phone number.'
        ], 400);
    }

    /**
     * Login
     *
     * @param Request $request
     * @param LoginAction $loginAction
     * @return JsonResponse
     * @throws ValidationException|ReflectionException
     */
    public function login(Request $request, LoginAction $loginAction): JsonResponse
    {
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required'
        ]);

        if ( $token = $loginAction( AuthenticationDTO::createFromRequest( $request ) ) ) {
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

    public function logout()
    {
        auth()->logout();

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
            'token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}

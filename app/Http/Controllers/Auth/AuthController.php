<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\VerifyEmailException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Services\Auth\AuthService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    private $authService;
    protected $auth;
    public function __construct(Guard $auth,)
    {
        $this->auth = $auth;
        $this->authService = new AuthService();
    }

    public function register(RegisterFormRequest $request)
    {
        try {
            return response()->json([
                'message' => 'User registered successfully.',
                'data' => $this->authService->register($request->all())
            ]);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], $e->getStatusCode());
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials, $request->remember)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'message' => 'Logged in successfully',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ], 200);
            } else {
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials'],
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (ThrottleRequestsException $e) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.',
            ], 429); // 429 Too Many Requests status code
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal server error',
            ], 500);
        }
    }

    public function logout()
    {
        try {
            return response()->json([
                'message' => 'User logout successfully.'
            ]);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }
    }
}

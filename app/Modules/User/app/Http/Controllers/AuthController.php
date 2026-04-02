<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\UserRole;
use Modules\User\Events\UserRegistered;
use Modules\User\Exceptions\UserUnauthorizedException;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws UserUnauthorizedException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = auth()->attempt($credentials);
        if (!$token) {
            throw new UserUnauthorizedException();
        }

        return response()->json(['token' => $token]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => UserRole::USER,
        ]);

        $token = JWTAuth::fromUser($user);

        UserRegistered::dispatch($user);

        return response()->json(['token' => $token]);
    }
}

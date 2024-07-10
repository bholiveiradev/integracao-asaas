<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new user
     *
     * @param AuthRegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $data = $request->except('password_confirmation');

        User::create($data);

        return response()->json(['message' => 'User was created.'], Response::HTTP_CREATED);
    }

    /**
    * Log in user and return token
    *
    * @param AuthLoginRequest $request
    *
    * @return JsonResponse
    */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        $user  = $request->user();
        $token = $user->createToken('PersonalAccessToken');

        return response()->json(['access_token' => $token->plainTextToken]);
    }

    /**
     * Get the authenticated user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * Logout user and revoke the token
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out.']);
    }
}

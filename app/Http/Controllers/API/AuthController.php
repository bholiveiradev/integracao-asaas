<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\{AuthLoginRequest, AuthRegisterRequest};
use App\Http\Resources\UserResource;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Http\Resources\Json\JsonResource;
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

        $user = User::create($data);

        $user->client()->create($data);

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
     * @return UserResource
     */
    public function me(Request $request): UserResource
    {
        $user = $request->user();

        return new UserResource($user);
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

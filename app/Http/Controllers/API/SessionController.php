<?php

namespace App\Http\Controllers\API;

use App\Enums\TokenAbility;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SessionController extends ApiController
{

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseFailed($validator->errors(), 422);
        }

        $validated = $validator->validate();

        // get user by email
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return $this->responseFailed('incorrect email or password', 422);
        }

        // validate password
        if (!\Hash::check($validated['password'], $user->password)) {
            return $this->responseFailed('incorrect email or password', 401);
        }

        // create token for logged in user
        $accessTokenExpiration = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], $accessTokenExpiration)->plainTextToken;

        $refreshTokenExpiration = Carbon::now()->addMinutes(config('sanctum.rt_expiration'));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], $refreshTokenExpiration)->plainTextToken;

        return $this->responseSuccess([
            [
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]
        ], 200);
    }

    public function me(Request $request)
    {
        return $this->responseSuccess([
            'user' => $request->user(),
        ], 200);
    }

    public function refreshToken(Request $request)
    {
        // revoke all tokens
        $user = $request->user();
        $user->tokens()->delete();

        // create new token
        $accessTokenExpiration = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], $accessTokenExpiration)->plainTextToken;

        $refreshTokenExpiration = Carbon::now()->addMinutes(config('sanctum.rt_expiration'));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], $refreshTokenExpiration)->plainTextToken;

        return $this->responseSuccess([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        // revoke all tokens
        $request->user()->tokens()->delete();

        return $this->responseSuccess([], 200);
    }
}

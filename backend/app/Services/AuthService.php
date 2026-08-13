<?php

namespace App\Services;

use App\DTO\LoginData;
use App\Enums\ErrorCode;
use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(LoginData $data): User
    {
        $credentials = ['email' => $data->email, 'password' => $data->password];

        if (! Auth::attempt($credentials, $data->remember)) {
            throw new ApiException(ErrorCode::InvalidCredentials);
        }

        session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}

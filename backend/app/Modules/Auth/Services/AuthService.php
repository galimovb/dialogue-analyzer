<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\DTO\LoginDTO;
use App\Modules\Shared\Enums\ErrorCode;
use App\Modules\Shared\Exceptions\ApiException;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(LoginDTO $data): User
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

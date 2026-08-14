<?php

namespace App\Modules\Users\Middleware;

use App\Modules\Shared\Enums\ErrorCode;
use App\Modules\Shared\Exceptions\ApiException;
use App\Modules\Users\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            throw new ApiException(ErrorCode::Unauthorized);
        }

        if ($user->role !== UserRole::Admin) {
            throw new ApiException(ErrorCode::Forbidden);
        }

        return $next($request);
    }
}

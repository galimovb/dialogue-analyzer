<?php

namespace App\Modules\Shared\Exceptions;

use App\Modules\Shared\ApiResponse;
use App\Modules\Shared\Enums\ErrorCode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    public static function render(Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof ApiException => ApiResponse::error($e->error, $e->getMessage(), $e->status),

            $e instanceof ValidationException => ApiResponse::error(
                ErrorCode::ValidationError,
                $e->validator->errors()->first(),
                extra: ['errors' => $e->errors()],
            ),

            $e instanceof AuthenticationException => ApiResponse::error(ErrorCode::Unauthorized),

            $e instanceof AuthorizationException => ApiResponse::error(ErrorCode::Forbidden),

            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException => ApiResponse::error(ErrorCode::NotFound),

            $e instanceof TokenMismatchException => ApiResponse::error(ErrorCode::CsrfTokenMismatch),

            $e instanceof HttpExceptionInterface => ApiResponse::error(
                ErrorCode::fromStatus($e->getStatusCode()),
                $e->getMessage() ?: null,
                $e->getStatusCode(),
            ),

            default => ApiResponse::error(
                ErrorCode::InternalError,
                config('app.debug') ? $e->getMessage() : null,
            ),
        };
    }
}

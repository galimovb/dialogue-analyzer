<?php

namespace App\Modules\Shared;

use App\Modules\Shared\Enums\ErrorCode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
    /**
     * Успешный ответ: { success: true, data: ... }
     */
    public static function success(mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    /**
     * Пагинированный ответ: { success: true, data: [...], pagination: {...} }
     *
     * @param  class-string<JsonResource>  $resource
     */
    public static function paginated(LengthAwarePaginator $paginator, string $resource): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $resource::collection($paginator->getCollection()),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more' => $paginator->hasMorePages(),
            ],
        ]);
    }

    /**
     * Ответ с ошибкой: { success: false, error: CODE, errorMessage: "..." }
     *
     * @param  array<string, mixed>  $extra
     */
    public static function error(ErrorCode $error, ?string $errorMessage = null, ?int $status = null, array $extra = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $error->value,
            'errorMessage' => $errorMessage ?? $error->message(),
            ...$extra,
        ], $status ?? $error->status());
    }
}

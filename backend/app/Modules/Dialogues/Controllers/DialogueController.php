<?php

namespace App\Modules\Dialogues\Controllers;

use App\Modules\Dialogues\Resources\DialogueResource;
use App\Modules\Dialogues\Resources\MessageResource;
use App\Modules\Dialogues\Services\DialogueService;
use App\Modules\Shared\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DialogueController extends Controller
{
    public function __construct(private readonly DialogueService $dialogues) {}

    public function index(): JsonResponse
    {
        return ApiResponse::paginated($this->dialogues->list(), DialogueResource::class);
    }

    public function show(int $id): JsonResponse
    {
        return ApiResponse::success(new DialogueResource($this->dialogues->find($id)));
    }

    public function messages(int $id): JsonResponse
    {
        return ApiResponse::paginated($this->dialogues->messages($id), MessageResource::class);
    }
}

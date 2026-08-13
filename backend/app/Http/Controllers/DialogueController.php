<?php

namespace App\Http\Controllers;

use App\Http\Resources\DialogueResource;
use App\Http\Resources\MessageResource;
use App\Services\DialogueService;
use App\Support\ApiResponse;
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

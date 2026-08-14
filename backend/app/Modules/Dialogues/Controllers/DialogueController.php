<?php

namespace App\Modules\Dialogues\Controllers;

use App\Modules\Dialogues\Resources\DialogueResource;
use App\Modules\Dialogues\Resources\MessageResource;
use App\Modules\Dialogues\Services\DialogueService;
use App\Modules\Shared\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class DialogueController extends Controller
{
    public function __construct(private readonly DialogueService $dialogues) {}

    public function index(Request $request): JsonResponse
    {
        return ApiResponse::paginated($this->dialogues->list($request->user()), DialogueResource::class);
    }

    public function show(int $id): JsonResponse
    {
        $dialogue = $this->dialogues->find($id);
        Gate::authorize('view', $dialogue);

        return ApiResponse::success(new DialogueResource($dialogue));
    }

    public function messages(int $id): JsonResponse
    {
        $dialogue = $this->dialogues->find($id);
        Gate::authorize('view', $dialogue);

        return ApiResponse::paginated($this->dialogues->messages($dialogue), MessageResource::class);
    }
}

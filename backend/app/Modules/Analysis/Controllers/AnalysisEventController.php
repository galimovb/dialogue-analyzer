<?php

namespace App\Modules\Analysis\Controllers;

use App\Modules\Analysis\Resources\AnalysisEventResource;
use App\Modules\Analysis\Services\AnalysisEventService;
use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Shared\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class AnalysisEventController extends Controller
{
    public function __construct(private readonly AnalysisEventService $events) {}

    public function index(int $id): JsonResponse
    {
        // События принадлежат диалогу — доступ проверяем той же Policy.
        Gate::authorize('view', Dialogue::query()->findOrFail($id));

        return ApiResponse::success(AnalysisEventResource::collection($this->events->forDialogue($id)));
    }
}

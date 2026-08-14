<?php

namespace App\Modules\Rules\Controllers;

use App\Modules\Rules\Requests\UpdateRuleRequest;
use App\Modules\Rules\Resources\RuleResource;
use App\Modules\Rules\Services\RuleService;
use App\Modules\Shared\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class RuleController extends Controller
{
    public function __construct(private readonly RuleService $rules) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success(RuleResource::collection($this->rules->list()));
    }

    public function update(UpdateRuleRequest $request, int $id): JsonResponse
    {
        return ApiResponse::success(new RuleResource($this->rules->update($id, $request->toDto())));
    }
}

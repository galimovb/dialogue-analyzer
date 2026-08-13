<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $auth) {}

    public function login(LoginRequest $request): JsonResponse
    {
        return ApiResponse::success($this->auth->login($request->toDto()));
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success($request->user());
    }

    public function logout(): JsonResponse
    {
        $this->auth->logout();

        return ApiResponse::success();
    }
}

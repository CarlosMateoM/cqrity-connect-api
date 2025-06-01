<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}


    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        return $this->authService->login($data);
    }
}

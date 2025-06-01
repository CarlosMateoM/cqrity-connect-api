<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $data): string
    {
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!Auth::attempt($data)) {

            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $user->tokens()->delete();

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logout(): void
    {
        $user = User::find(Auth::id());

        $user->currentAccessToken()->delete();
    }
}

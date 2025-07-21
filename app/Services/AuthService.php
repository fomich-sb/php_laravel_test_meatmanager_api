<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function login(array $credentials): ?string
    {
        if (!auth()->attempt($credentials)) {
            return null;
        }
        
        $user = User::where('phone', $credentials['phone'])->first();
        return $user->createToken('auth_token')->plainTextToken;
    }
}
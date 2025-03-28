<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\UserPerssionsEnums;

class AuthService
{
    public function register(array $data): string
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'permission_id' => UserPerssionsEnums::ADMIN,  
        ]);
        
        return JWTAuth::fromUser($user);
    }
    
    public function login(array $credentials): string
    {
        if (!$token = auth('api')->attempt($credentials)) {
            abort(401, 'Unauthorized');
        }
    
        return $token;
    }

    public function changePermission($id, int $permission_id)
    {
        $order = User::findOrFail($id);

        if (auth()->user()->permissions->name !== 'admin') {
            return ['error' => 'Você não tem permissão para essa ação!', 'status' => 403];
        }

        $order->update(['permission_id' => $permission_id]);
      
        return ['message' => 'Permissão atualizada com sucesso', 'status' => 200];
    }
}

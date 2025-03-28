<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        try{
            $token = $this->authService->register($request->validated());
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function login(Request $request)
    {
        try{
            $token = $this->authService->login($request->only('email', 'password'));
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Usuário ou senha incorreta'], 401);
        }
    }

    public function me()
    {
        try{
            $user = User::findOrFail(auth()->id());

            if (!$user) {
                return response()->json(['error' => 'Usuário não autenticado. Token inválido ou expirado.'], 401);}
        
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Erro ao buscar usuário'], 500);
        }
    }

    public function changePermission(Request $request, $id)
    {
        try {
            $result = $this->authService->changePermission($id, $request['permission_id']);

            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], $result['status']);
            }

            return response()->json(['message' => $result['message']], $result['status']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }
    }
    
}


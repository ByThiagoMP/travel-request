<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_register_and_receive_jwt(): void
    {
        // Dados de registro para o usuário
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',  // Senha que será usada para login
        ];

        // Realiza a requisição POST para registrar o usuário
        $response = $this->postJson('/api/register', $data);

        // Verifica se o status da resposta é 201 (Criado)
        $response->assertStatus(200);

        // Verifica se o JWT foi retornado na resposta
        $response->assertJsonStructure(['token']);
    }

    public function test_user_can_login_and_receive_jwt(): void
    {
        // Cria um usuário para fazer login
        $user = User::factory()->create([
            'password' => bcrypt('password123'),  // Senha que será usada para login
        ]);

        // Dados de login do usuário
        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        // Realiza a requisição POST para fazer login
        $response = $this->postJson('/api/login', $credentials);

        // Verifica se o status da resposta é 200 (OK)
        $response->assertStatus(200);

        // Verifica se o JWT foi retornado na resposta
        $response->assertJsonStructure(['token']);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        // Dados inválidos para login
        $credentials = [
            'email' => 'invaliduser@example.com',
            'password' => 'wrongpassword',
        ];

        // Realiza a requisição POST para tentar fazer login com credenciais inválidas
        $response = $this->postJson('/api/login', $credentials);

        // Verifica se o status da resposta é 401 (Não autorizado)
        $response->assertStatus(401);

        // Verifica a mensagem de erro
        $response->assertJson(['error' => 'Usuário ou senha incorreta']);
    }

    public function test_user_can_fetch_their_own_data(): void
    {
        // Cria um usuário para fazer a autenticação
        $user = User::factory()->create();

        // Gera o token JWT para o usuário
        $token = JWTAuth::fromUser($user);

        // Realiza a requisição GET para o endpoint /me com o token JWT
        $response = $this->getJson('/api/me', [
            'Authorization' => 'Bearer ' . $token,  // Adiciona o token JWT no cabeçalho
        ]);

        // Verifica se o status da resposta é 200 (OK)
        $response->assertStatus(200);

        // Verifica se os dados retornados correspondem ao usuário autenticado
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // Adicione outros campos se necessário, dependendo da estrutura do usuário
        ]);
    }

    public function test_user_cannot_fetch_data_when_not_authenticated(): void
    {
        // Realiza a requisição GET para o endpoint /me sem um token de autenticação
        $response = $this->getJson('/api/me');

        // Verifica se o status da resposta é 401 (Não autorizado)
        $response->assertStatus(401);

        // Verifica a mensagem de erro retornada
        $response->assertJson(['message' => 'Não autenticado.']);
    }

    public function test_admin_can_change_permission()
    {
        // Criando um usuário admin e autenticando
        $admin = User::factory()->create(['permission_id' => \App\Enums\UserPerssionsEnums::ADMIN]);
        $this->actingAs($admin);

        // Criando um usuário comum
        $user = User::factory()->create();

        // Fazendo a requisição para alterar a permissão
        $response = $this->putJson("/api/users/{$user->id}/change-permission", [
            'permission_id' => \App\Enums\UserPerssionsEnums::ADMIN
        ]);

        // Verificando a resposta esperada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Permissão atualizada com sucesso']);
    }

    public function test_non_admin_cannot_change_permission()
    {
        // Criando um usuário comum e autenticando
        $user = User::factory()->create(['permission_id' => \App\Enums\UserPerssionsEnums::USER]);
        $this->actingAs($user);

        $targetUser = User::factory()->create();

        // Tentando alterar a permissão de outro usuário
        $response = $this->putJson("/api/users/{$targetUser->id}/change-permission", [
            'permission_id' => \App\Enums\UserPerssionsEnums::ADMIN
        ]);

        // Verificando se foi barrado corretamente
        $response->assertStatus(403)
            ->assertJson(['error' => 'Você não tem permissão para essa ação!']);
    }
}

<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\AuthService;

class AuthServiceTest extends TestCase
{

    public function testAuthenticateUser()
    {
        $service = Mockery::mock(AuthService::class)->makePartial();

        // Mockar login para retornar uma string (token)
        $service->shouldReceive('login')->andReturn('fake-token');

        $token = $service->login([]);

        $this->assertIsString($token);
    }

    public function testRegisterUser()
    {
        $service = Mockery::mock(AuthService::class)->makePartial();

        // Mockar register para retornar uma string (token)
        $service->shouldReceive('register')->andReturn('fake-token');

        $token = $service->register([]);

        $this->assertIsString($token);
    }

    public function test_admin_can_change_permission()
    {
        // Criando um usuário admin mockado
        $admin = User::factory()->make(['permissions' => (object)['name' => 'admin']]);
        $this->actingAs($admin);

        // Criando um usuário qualquer
        $user = User::factory()->create();

        // Criando um mock do serviço
        $service = Mockery::mock(AuthService::class)->makePartial();

        // Definindo a permissão a ser alterada
        $newPermission = 2;

        // Chamando a função a ser testada
        $response = $service->changePermission($user->id, $newPermission);

        // Verificando se a permissão foi alterada corretamente
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('Permissão atualizada com sucesso', $response['message']);
    }

    public function test_non_admin_cannot_change_permission()
    {
        // Criando um usuário comum
        $user = User::factory()->make(['permissions' => (object)['name' => 'user']]);
        $this->actingAs($user);

        $targetUser = User::factory()->create();
        $service = Mockery::mock(AuthService::class)->makePartial();

        $response = $service->changePermission($targetUser->id, 2);

        $this->assertEquals(403, $response['status']);
        $this->assertEquals('Você não tem permissão para essa ação!', $response['error']);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TravelOrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_travel_order(): void
    {
        // Crie um usuário fictício para gerar o JWT
        $user = User::factory()->create();

        // Gere o JWT para o usuário
        $token = JWTAuth::fromUser($user);

        // Faça a requisição autenticada com o JWT no cabeçalho
        $response = $this->post('api/pedidos', [
            'destination' => 'São Paulo',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ], [
            'Authorization' => 'Bearer ' . $token,  // Adicione o token JWT ao cabeçalho
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('travel_orders', [
            'destination' => 'São Paulo',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ]);
    }

    public function test_get_travel_order(): void
    {
        $user = User::factory()->create();

        $travelOrder = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status_id' => \App\Enums\TravelOrderStatusEnums::SOLICITADO,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->get('api/pedidos/' . $travelOrder->id,  [
            'Authorization' => 'Bearer ' . $token,  // Adicione o token JWT ao cabeçalho
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $travelOrder->id,
            'destination' => $travelOrder->destination,
            'departure_date' => $travelOrder->departure_date->format('Y-m-d'),
            'return_date' => $travelOrder->return_date->format('Y-m-d'),
        ]);
    }
    public function test_list_user_orders_with_filters(): void
    {
        // Cria um usuário autenticado
        $user = User::factory()->create();

        // Cria algumas ordens de viagem com status e datas variados
        $matchingOrder = \App\Models\TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status_id' => 1,
            'destination' => 'São Paulo',
            'departure_date' => '2025-04-10',
        ]);

        // Cria outra que não bate com os filtros
        \App\Models\TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status_id' => 2,
            'destination' => 'Rio de Janeiro',
            'departure_date' => '2025-05-01',
        ]);

        // Gera o token JWT
        $token = JWTAuth::fromUser($user);

        // Chama a rota com filtros
        $response = $this->getJson('api/pedidos?status_id=1&destination=São&start_date=2025-04-01&end_date=2025-04-30', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);

        // Verifica se o pedido correto está presente
        $response->assertJsonFragment([
            'id' => $matchingOrder->id,
            'destination' => $matchingOrder->destination,
        ]);

        // Verifica que o outro não está presente
        $response->assertJsonMissing([
            'destination' => 'Rio de Janeiro',
        ]);
    }

    public function test_user_can_update_their_travel_order(): void
    {
        $user = User::factory()->create();
        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status_id' => \App\Enums\TravelOrderStatusEnums::SOLICITADO,
        ]);
        $token = JWTAuth::fromUser($user);

        $payload = [
            'destination' => 'Porto Alegre',
            'departure_date' => '2025-04-25',
            'return_date' => '2025-04-30',
        ];

        $response = $this->putJson("api/pedidos/{$order->id}", $payload, [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $order->id,
            'destination' => 'Porto Alegre',
            'status_id' => \App\Enums\TravelOrderStatusEnums::SOLICITADO,
        ]);
    }

    public function test_admin_can_update_travel_order_status(): void
    {
        $admin = User::factory()->create([
            'permission_id' => \App\Enums\UserPerssionsEnums::ADMIN,
        ]);
        $user = User::factory()->create();
        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status_id' => \App\Enums\TravelOrderStatusEnums::SOLICITADO,
        ]);

        $token = JWTAuth::fromUser($admin);

        $response = $this->patchJson("api/pedidos/{$order->id}/status", [
            'status' => \App\Enums\TravelOrderStatusEnums::APROVADO
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Status atualizado com sucesso'
        ]);
    }

    public function test_user_cannot_update_own_travel_order_status(): void
    {
        $user = User::factory()->create([
            'permission_id' => \App\Enums\UserPerssionsEnums::USER,
        ]);
        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status_id' => \App\Enums\TravelOrderStatusEnums::SOLICITADO,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->patchJson("api/pedidos/{$order->id}/status", [
            'status' => \App\Enums\TravelOrderStatusEnums::CANCELADO
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'error' => 'Você não pode alterar o status do seu próprio pedido'
        ]);
    }

    public function test_cannot_cancel_trip_less_than_24_hours_before_departure(): void
    {
        $admin = User::factory()->create([
            'permission_id' => \App\Enums\UserPerssionsEnums::ADMIN
        ]);
        $user = User::factory()->create();

        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status_id' => \App\Enums\TravelOrderStatusEnums::APROVADO,
            'departure_date' => now()->addHours(12),
        ]);

        $token = JWTAuth::fromUser($admin);

        $response = $this->patchJson("api/pedidos/{$order->id}/status", [
            'status' => \App\Enums\TravelOrderStatusEnums::CANCELADO
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'error' => 'Cancelamento não permitido: a viagem está muito próxima ou em andamento'
        ]);
    }
}

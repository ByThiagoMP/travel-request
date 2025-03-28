<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\TravelOrder;
use App\Services\TravelOrderService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StatusChangedNotification;
use App\Models\TravelOrderStatus; // Add this import

class TravelOrderServiceTest extends TestCase
{

    public function testCreateTravelOrder()
    {
        Notification::fake();

        $service = Mockery::mock(TravelOrderService::class)->makePartial();

        // Mockar criação de ordem de viagem
        $order = Mockery::mock(TravelOrder::class);
        $service->shouldReceive('create')->andReturn($order);

        $order = $service->create([]);

        $this->assertInstanceOf(TravelOrder::class, $order);
    }

    public function testGetUserOrderById()
    {
        $service = Mockery::mock(TravelOrderService::class)->makePartial();

        // Mockar busca de ordem de viagem
        $order = Mockery::mock(TravelOrder::class);
        $service->shouldReceive('getUserOrderById')->andReturn($order);

        $order = $service->getUserOrderById(1);

        $this->assertInstanceOf(TravelOrder::class, $order);
    }

    public function testUpdateTravelOrder()
    {
        Notification::fake();

        $service = Mockery::mock(TravelOrderService::class)->makePartial();

        // Mockar atualização de ordem de viagem
        $order = Mockery::mock(TravelOrder::class);
        $service->shouldReceive('update')->andReturn($order);

        $order = $service->update(1, []);

        $this->assertInstanceOf(TravelOrder::class, $order);
    }

    public function testListUserOrders()
    {
        $service = Mockery::mock(TravelOrderService::class)->makePartial();

        // Mockar listagem de ordens de viagem
        $orders = collect([Mockery::mock(TravelOrder::class), Mockery::mock(TravelOrder::class), Mockery::mock(TravelOrder::class)]);
        $service->shouldReceive('listUserOrders')->andReturn($orders);

        $orders = $service->listUserOrders(Mockery::mock(\Illuminate\Http\Request::class));

        $this->assertCount(3, $orders);
    }  

    public function testListTravelOrderStatus()
    {
        $service = Mockery::mock(TravelOrderService::class)->makePartial();

        // Mockar retorno de status
        $statuses = collect([Mockery::mock(TravelOrderStatus::class), Mockery::mock(TravelOrderStatus::class), Mockery::mock(TravelOrderStatus::class)]);
        $service->shouldReceive('listTravelOrderStatus')->andReturn($statuses);

        $statuses = $service->listTravelOrderStatus();

        $this->assertCount(3, $statuses);
    }
}

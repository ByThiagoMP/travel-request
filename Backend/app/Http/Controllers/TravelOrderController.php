<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TravelOrderService;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TravelOrderController extends Controller
{
    protected TravelOrderService $service;

    public function __construct(TravelOrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $orders = $this->service->listUserOrders($request);
            return response()->json($orders);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Nenhum pedido encontrado'], 404);
        }
    }

    public function store(StoreTravelOrderRequest $request)
    {
        try {
        $order = $this->service->create($request->validated());
        return response()->json($order, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Erro ao criar pedido'], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = $this->service->getUserOrderById($id);

            return response()->json($order);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }
    }

    public function update($id, Request $request)
    {
        try {
        $result = $this->service->update($id, $request->all());
        return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }
    }
    
    public function updateStatus(UpdateTravelOrderStatusRequest $request, $id)
    {
        try {
            $result = $this->service->updateStatus($id, $request['status']);

            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], $result['status']);
            }
    
            return response()->json(['message' => $result['message']], $result['status']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }
    }

    public function traverOrderStatus()
    {
        try {
            $orders = $this->service->listTravelOrderStatus();
            return response()->json($orders);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Nenhum Status'], 404);
        }
    }
}


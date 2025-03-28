<?php

namespace App\Services;

use App\Models\TravelOrder;
use App\Models\TravelOrderStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enums\TravelOrderStatusEnums;
use App\Notifications\StatusChangedNotification;

class TravelOrderService
{
    public function listUserOrders(Request $request)
    {
        $user = auth()->user();

        $query = TravelOrder::with('status', 'user')
            ->accessibleBy($user);

        if ($request->has('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('departure_date', [$request->start_date, $request->end_date]);
        }

        return $query->paginate(10);
    }

    public function create(array $data)
    {
        $order = TravelOrder::create([
            ...$data,
            'user_id' => auth()->id(),
            'status_id' => TravelOrderStatusEnums::SOLICITADO,
        ]);

        $order->user->notify(new StatusChangedNotification("Seu pedido: $order->id foi criado com sucesso"));

        return $order;
    }

    public function getUserOrderById($id)
    {
        return TravelOrder::where('user_id', auth()->id())->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $order = TravelOrder::with(['user', 'status'])->findOrFail($id);
        $data['status_id'] = TravelOrderStatusEnums::SOLICITADO;
        $order->update($data);

        $order->user->notify(new StatusChangedNotification("Seu pedido: $order->id foi atualizado com sucesso"));

        return $order;
    }


    public function updateStatus($id, int $status)
    {
        $order = TravelOrder::findOrFail($id);

        if ($order->user_id == auth()->id() || auth()->user()->permissions->name !== 'admin') {
            return ['error' => 'Você não pode alterar o status do seu próprio pedido', 'status' => 403];
        }
   
        if ($order->status->id === TravelOrderStatusEnums::APROVADO && $status === TravelOrderStatusEnums::CANCELADO) {
            $now = Carbon::now();
            $departure = Carbon::parse($order->departure_date);
            if ($departure->diffInHours($now) < 24) { 
                return ['error' => 'Cancelamento não permitido: a viagem está muito próxima ou em andamento', 'status' => 403];
            }
        }

        $order->update(['status_id' => $status]);
        $updatedStatus = $order->fresh()->status->name;
        $order->user->notify(new StatusChangedNotification("Seu pedido: $order->id foi $updatedStatus"));

        return ['message' => 'Status atualizado com sucesso', 'status' => 200];
    }


    public function listTravelOrderStatus()
    {
        return TravelOrderStatus::get();
    }
}

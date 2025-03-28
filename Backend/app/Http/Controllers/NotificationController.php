<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;

        return response()->json($notifications);
    }

    public function markAsRead($notificationId)
    {
        // Recupera a notificação do usuário autenticado pelo ID
        $notification = auth()->user()->unreadNotifications->find($notificationId);

        // Marca a notificação como lida
        $notification->markAsRead();

        return response()->json(['message' => 'Notificação marcada como lida']);
    }
}

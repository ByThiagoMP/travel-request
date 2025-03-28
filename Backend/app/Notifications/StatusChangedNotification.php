<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChangedNotification extends Notification
{
    use Queueable;

    /**
     * The travel order instance.
     *
     * @var mixed
     */
    private $travelOrder;

    /**
     * The status of the travel order.
     *
     * @var mixed
     */
    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    
    /**
     * Get the array representation of the notification.
     *
     * Esse método pode ser usado para serializar a notificação para outras representações,
     * se necessário.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'travel_order_id' => $this->travelOrder->id,
            'destination'      => $this->travelOrder->destination,
        ];
    }

     /**
     * Get the array representation of the notification.
     *
     * Esse método é utilizado, por exemplo, para salvar a notificação no banco de dados.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return['messagem' => $this->message];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifNewOrders extends Notification
{
    use Queueable;

    private $detailOrders;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($detailOrders)
    {
        $this->detailOrders = $detailOrders;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'nomor_order_receipt' => $this->detailOrders->nomor_order_receipt,
            'custid' => $this->detailOrders->custid,
            'name' => $this->detailOrders->custname_order,
            'message' => $this->detailOrders->custname_order . ' Just Ordered'
        ];
    }
}

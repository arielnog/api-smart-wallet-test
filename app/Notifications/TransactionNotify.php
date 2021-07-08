<?php

namespace App\Notifications;

use App\Models\WalletLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotify extends Notification
{
    use Queueable;

    private $walletLog;

    public function __construct(WalletLog $walletLog)
    {
        $this->walletLog = $walletLog;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->subject("Operação  - {$this->walletLog->typeTransaction->name}")
            ->greeting("Prezado,")
            ->line("Foi realizado na sua carteira um {$this->walletLog->typeTransaction->name}, no valor de R$".abs($this->walletLog->value))
            ->from('ariel@example.com', 'Ariel Nogueira');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

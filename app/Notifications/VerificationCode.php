<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Leonis\Notifications\EasySms\Channels\EasySmsChannel;
use Leonis\Notifications\EasySms\Messages\EasySmsMessage;

class VerificationCode extends Notification
{
    use Queueable;
    private $code;
    /**
     * Create a new notification instance.
     * @return void
     */
    public function __construct($code)
    {
        $this->code =$code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [EasySmsChannel::class];
    }

    public function toEasySms($notifiable)
    {
        return (new EasySmsMessage())
            ->setTemplate('SMS_154950909')
            ->setData(['code'=>$this->code]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

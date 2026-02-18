<?php

declare(strict_types=1);

namespace Shared\Services\Sms\Support;

use Illuminate\Notifications\Notification;
use Shared\Services\Sms\Concerns\SmsMessageInterface;

class SmsChannel
{
    private SmsMessageInterface $message;

    public function __construct(SmsMessageInterface $message)
    {
        $this->message = $message;
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $data = $notification->toSms($notifiable);

        $this->message->send(
            phone: $notifiable->phone,
            args: $data->args,
            temp: $data->temp,
        );
    }
}

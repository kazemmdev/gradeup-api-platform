<?php

namespace Shared\Services\Sms;

use Illuminate\Support\Facades\Http;
use Shared\Services\Sms\Concerns\SmsMessageInterface;
use Shared\Services\Sms\Exceptions\SmsMessageException;
use Shared\Services\Sms\Templates\SmsTemplateInterface;

class KavenegarSmsMessage implements SmsMessageInterface
{
    public function send(string $phone, array|string $args, SmsTemplateInterface $temp): void
    {
        try {
            if (config('services.sms.api_key') !== 'dev') {
                $params = [
                    'receptor' => $phone,
                    'message' => $temp->build(is_array($args) ? $args : ['message' => $args]),
                    'sender' => config('services.sms.sender'),
                ];
                Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'charset' => 'utf-8',
                ])->asForm()->post(
                    'https://api.kavenegar.com/v1/'.config('services.sms.api_key').'/sms/send.json',
                    $params
                );
            }
        } catch (\Exception $e) {
            SmsMessageException::because(
                'SMS Error: '.$e->getMessage()
            );
        }
    }
}

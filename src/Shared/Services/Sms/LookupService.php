<?php

namespace Shared\Services\Sms;

use Illuminate\Support\Facades\Http;
use Shared\Services\Sms\Exceptions\SmsMessageException;

class LookupService
{
    public function send(string $phone, array|string $args): void
    {
        try {
            if (config('services.sms.api_key') !== 'dev') {
                Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'charset' => 'utf-8',
                ])->asForm()->post(
                    url: 'https://api.kavenegar.com/v1/'.config('services.sms.api_key').'/verify/lookup.json',
                    data: [
                        'receptor' => $phone,
                        'token' => $args['code'],
                        'template' => config('services.sms.templates.verify'),
                    ]
                );
            }
        } catch (\Exception $e) {
            SmsMessageException::because(
                'SMS Error: '.$e->getMessage()
            );
        }
    }
}

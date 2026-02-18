<?php

declare(strict_types=1);

namespace Shared\Services\Sms\Templates;

use Module\Setting\Supports\AppSettings;

class SubExpiredTemplate extends SmsTemplate implements SmsTemplateInterface
{
    public function build(array $args): string
    {
        $settings = app(AppSettings::class);
        $appName =  $settings->app_name  ?? 'گریدآپ';
        $appUrl = $settings->app_url ?? 'https://gradeup.app';

        return sprintf(
            'اشتراکت تو %s تموم شده!
از بقیه عقب نمون و تا فرصت هست، همین حالا از طریق آدرس زیر تمدیدش کن.

%s',
            $appName,
            $appUrl
        );
    }
}

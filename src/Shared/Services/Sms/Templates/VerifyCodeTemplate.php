<?php

declare(strict_types=1);

namespace Shared\Services\Sms\Templates;

use Module\Setting\Supports\AppSettings;

class VerifyCodeTemplate extends SmsTemplate implements SmsTemplateInterface
{
    public function build(array $args): string
    {
        $settings = app(AppSettings::class);
        $appName = $settings->app_name ?? 'گریدآپ';
        $appUrl = $settings->app_url ?? 'https://gradeup.app';

        return sprintf(
            'کد تایید برنامه %s: 
code: %s
%s',
            $appName,
            $args['code'] ?? '1234',
            $appUrl
        );
    }
}

<?php

namespace Shared\Services\Sms\Concerns;

use Shared\Services\Sms\Templates\SmsTemplateInterface;

interface SmsMessageInterface
{
    /**
     * Notify user by SMS
     */
    public function send(string $phone, array|string $args, SmsTemplateInterface $temp): void;
}

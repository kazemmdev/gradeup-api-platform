<?php

declare(strict_types=1);

namespace Shared\Services\Sms\Templates;

interface SmsTemplateInterface
{
    /**
     * Build the SMS message content.
     */
    public function build(array $args): string;
}

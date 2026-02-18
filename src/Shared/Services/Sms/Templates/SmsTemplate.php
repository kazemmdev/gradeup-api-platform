<?php

declare(strict_types=1);

namespace Shared\Services\Sms\Templates;

abstract class SmsTemplate implements SmsTemplateInterface
{
    abstract public function build(array $args): string;
}

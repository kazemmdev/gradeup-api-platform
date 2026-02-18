<?php

declare(strict_types=1);

namespace Shared\Services\Sms\Support;

use Shared\Services\Sms\Templates\SmsTemplateInterface;

class SmsMessage
{
    public array $args = [];

    public SmsTemplateInterface $temp;

    public function args(array $args): static
    {
        $this->args = $args;

        return $this;
    }

    public function template(SmsTemplateInterface $temp): static
    {
        $this->temp = $temp;

        return $this;
    }
}

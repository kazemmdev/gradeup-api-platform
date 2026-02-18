<?php

namespace Shared\Services\Sms\Exceptions;

use Kazemmdev\HttpStatus\Http;

class SmsMessageException extends \Exception
{
    public static function because(string $message): self
    {
        return new self($message, Http::FORBIDDEN());
    }
}

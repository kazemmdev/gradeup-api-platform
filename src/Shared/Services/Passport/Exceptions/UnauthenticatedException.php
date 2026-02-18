<?php

namespace Shared\Services\Passport\Exceptions;

use Kazemmdev\HttpStatus\Http;
use Shared\Exceptions\ApiException;

class UnauthenticatedException extends ApiException
{
    public static function because(string $message): self
    {
        return new self($message, Http::FORBIDDEN());
    }
}

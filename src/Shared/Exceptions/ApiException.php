<?php

namespace Shared\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Shared\Contract\ApiResponse;

abstract class ApiException extends Exception
{
    use ApiResponse;

    public function render(): JsonResponse
    {
        return $this->error($this->getMessage(), $this->getCode());
    }

    abstract public static function because(string $message): self;
}

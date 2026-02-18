<?php

namespace Shared\Services\Passport\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface PhoneResolverInterface
{
    public function resolveUserByPhoneActivationCode(string $phone, string $code): ?Authenticatable;
}

<?php

namespace Shared\Services\Passport\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface SocialResolverInterface
{
    public function resolveUserByProviderCredentials(string $provider, string $accessToken): ?Authenticatable;
}

<?php

namespace Shared\Services\Passport\Resolvers;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Facades\Socialite;
use Module\Auth\Actions\UpsertUserBySocialAction;
use Shared\Services\Passport\Contracts\SocialResolverInterface;

class SocialResolver implements SocialResolverInterface
{
    public function resolveUserByProviderCredentials(string $provider, string $accessToken): ?Authenticatable
    {
        $providerUser = null;

        try {
            $providerUser = Socialite::driver($provider)->userFromToken($accessToken);
        } catch (Exception $e) {
        }

        if ($providerUser) {
            return UpsertUserBySocialAction::execute($providerUser, $provider);
        }

        return null;
    }
}

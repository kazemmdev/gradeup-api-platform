<?php

namespace Shared\Services\Passport\Resolvers;

use Illuminate\Contracts\Auth\Authenticatable;
use Module\Auth\Actions\UpsertUserByPhoneAction;
use Module\Auth\Models\VerificationCode;
use Shared\Services\Passport\Contracts\PhoneResolverInterface;

class PhoneResolver implements PhoneResolverInterface
{
    public function resolveUserByPhoneActivationCode(string $phone, string $code): ?Authenticatable
    {
        $verification = VerificationCode::query()->where('target', $phone)->first();

        if ($verification && $verification->isVerified()) {
            return UpsertUserByPhoneAction::execute($phone);
        }

        return null;
    }
}

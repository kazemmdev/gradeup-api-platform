<?php

declare(strict_types=1);

namespace Shared\Services\Passport;

use Laravel\Passport\Client as OClient;
use Module\Auth\DTOs\PhoneData;
use Shared\Services\Passport\Exceptions\UnauthenticatedException;
use Symfony\Component\HttpFoundation\Request;

class PassportService
{
    public function grantSocialToken($provider, $provider_token)
    {
        return $this->makeRequest([
            'grant_type' => 'social',
            'social_provider' => $provider,
            'access_token' => $provider_token,
        ]);
    }

    public function grantPhoneToken(PhoneData $data): mixed
    {
        return $this->makeRequest([
            'grant_type' => 'phone',
            'phone_number' => $data->phone,
            'verification_code' => $data->code,
            'scope' => '',
        ]);
    }

    public function grantPasswordToken(array $params): mixed
    {
        return $this->makeRequest([
            'grant_type' => 'password',
            'username' => $params['email'],
            'password' => $params['password'],
        ]);
    }

    public function grantRefreshToken(string $refreshToken)
    {
        return $this->makeRequest([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     * @throws UnauthenticatedException
     */
    protected function makeRequest(array $request)
    {
        $client = OClient::query()->where('provider', 'users')->first();
        $http_request = Request::create('oauth/token', 'post', [...$request,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
        ]);

        $http_response = json_decode(app()->handle($http_request)->getContent());

        if (! isset($http_response->refresh_token)) {
            throw UnauthenticatedException::because('Server Error!');
        }

        return $http_response;
    }
}

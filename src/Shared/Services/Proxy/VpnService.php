<?php

declare(strict_types=1);

namespace Shared\Services\Proxy;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VpnService
{
    public function get(): ?array
    {
        $ip = request('ip');
        $apiKey = config('services.proxy.proxycheck.key');
        $baseUrl = config('services.proxy.proxycheck.baseUrl');

        try {
            $response = Http::get("$baseUrl$ip", [
                'key' => $apiKey,
                'vpn' => 1,
                'asn' => 1,
                'node' => 1,
            ]);

            if ($response->successful()) {
                $data = Arr::get($response->json(), $ip, []);

                return [
                    'is_vpn' => $data['type'] === 'VPN',
                    'country_code' => $data['isocode'] ?? null,
                    //                    'risk_score'   => $data['risk'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return ['is_vpn' => false];
    }
}

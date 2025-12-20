<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpGeolocation
{
    protected string $apiUrl = 'http://ip-api.com/json/';

    /**
     * Get location data from IP address
     */
    public function locate(string $ip): ?array
    {
        // Don't geolocate local/private IPs
        if ($this->isPrivateIp($ip)) {
            return null;
        }

        // Check cache first (cache for 24 hours)
        $cacheKey = 'ip_geolocation_' . $ip;

        return Cache::remember($cacheKey, 86400, function () use ($ip) {
            try {
                $response = Http::timeout(5)->get($this->apiUrl . $ip);

                if ($response->successful()) {
                    $data = $response->json();

                    if ($data['status'] === 'success') {
                        return [
                            'country' => $data['country'] ?? null,
                            'region' => $data['regionName'] ?? null,
                            'city' => $data['city'] ?? null,
                            'latitude' => $data['lat'] ?? null,
                            'longitude' => $data['lon'] ?? null,
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('IP geolocation failed', [
                    'ip' => $ip,
                    'error' => $e->getMessage(),
                ]);
            }

            return null;
        });
    }

    /**
     * Check if IP is private/local
     */
    protected function isPrivateIp(string $ip): bool
    {
        // Local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return true;
        }

        // Private IP ranges
        $privateRanges = [
            '10.0.0.0' => '10.255.255.255',
            '172.16.0.0' => '172.31.255.255',
            '192.168.0.0' => '192.168.255.255',
        ];

        $longIp = ip2long($ip);
        if ($longIp === false) {
            return false;
        }

        foreach ($privateRanges as $start => $end) {
            if ($longIp >= ip2long($start) && $longIp <= ip2long($end)) {
                return true;
            }
        }

        return false;
    }
}

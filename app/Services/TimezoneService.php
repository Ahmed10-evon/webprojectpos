<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around TimezoneDB's "get-time-zone" endpoint
 * (https://timezonedb.com/api). The key stays server-side (config ->.env),
 * never sent to the browser.
 */
class TimezoneService
{
    protected const CACHE_MINUTES = 30;

    /**
     * Get the current date/time for the configured zone.
     *
     * Returns null on any failure (missing key, network error, bad zone)
     * so an outage here never breaks the dashboard.
     */
    public function current(): ?array
    {
        $key = config('services.timezonedb.key');
        $gateway = rtrim(config('services.timezonedb.gateway'), '/');
        $zone = config('services.timezonedb.zone');

        if (! $key) {
            return null;
        }

        $cacheKey = "timezone.current.{$zone}";

        return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_MINUTES), function () use ($key, $gateway, $zone) {
            try {
                $response = Http::timeout(5)->get("{$gateway}/v2.1/get-time-zone", [
                    'key' => $key,
                    'format' => 'json',
                    'by' => 'zone',
                    'zone' => $zone,
                ]);

                if (! $response->successful()) {
                    Log::warning('TimezoneDB API request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return null;
                }

                $data = $response->json();

                if (($data['status'] ?? null) !== 'OK') {
                    Log::warning('TimezoneDB API returned non-OK status', $data);

                    return null;
                }

                return [
                    'zone' => $data['zoneName'] ?? $zone,
                    'country' => $data['countryName'] ?? null,
                    'formatted' => $data['formatted'] ?? null,
                    'gmt_offset_hours' => isset($data['gmtOffset']) ? $data['gmtOffset'] / 3600 : null,
                    'abbreviation' => $data['abbreviation'] ?? null,
                ];
            } catch (\Throwable $e) {
                Log::warning('TimezoneDB API exception: '.$e->getMessage());

                return null;
            }
        });
    }
}

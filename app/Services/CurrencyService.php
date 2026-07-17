<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around currencyapi.com's "latest" endpoint
 * (https://currencyapi.com/docs). The key stays server-side, never sent to
 * the browser. Cached for longer than weather/timezone since exchange
 * rates barely move minute-to-minute and free-tier plans have a monthly
 * request cap.
 */
class CurrencyService
{
    protected const ENDPOINT = 'https://api.currencyapi.com/v3/latest';
    protected const CACHE_HOURS = 6;

    /**
     * Get the latest exchange rates from the configured base currency to
     * the configured target currencies (e.g. 1 USD -> BDT/EUR/GBP).
     *
     * Returns null on any failure so an outage here never breaks the
     * dashboard.
     */
    public function rates(): ?array
    {
        $key = config('services.currencyapi.key');
        $base = config('services.currencyapi.base');
        $targets = config('services.currencyapi.targets');

        if (! $key) {
            return null;
        }

        $cacheKey = "currency.rates.{$base}.{$targets}";

        return Cache::remember($cacheKey, now()->addHours(self::CACHE_HOURS), function () use ($key, $base, $targets) {
            try {
                $response = Http::timeout(5)
                    ->withHeaders(['apikey' => $key])
                    ->get(self::ENDPOINT, [
                        'base_currency' => $base,
                        'currencies' => $targets,
                    ]);

                if (! $response->successful()) {
                    Log::warning('CurrencyAPI request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return null;
                }

                $data = $response->json('data') ?? [];

                if (empty($data)) {
                    return null;
                }

                $rates = collect($data)->map(fn ($row) => $row['value'] ?? null)->filter()->toArray();

                return [
                    'base' => $base,
                    'rates' => $rates,
                    'fetched_at' => now(),
                ];
            } catch (\Throwable $e) {
                Log::warning('CurrencyAPI exception: '.$e->getMessage());

                return null;
            }
        });
    }
}

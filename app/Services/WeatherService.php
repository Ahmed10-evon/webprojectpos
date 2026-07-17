<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around OpenWeatherMap's Current Weather Data API
 * (https://openweathermap.org/current). The API key stays server-side
 * (config/services.php -> .env), never sent to the browser — the
 * dashboard only ever receives the already-fetched result.
 */
class WeatherService
{
    protected const ENDPOINT = 'https://api.openweathermap.org/data/2.5/weather';
    protected const CACHE_MINUTES = 30;

    /**
     * Get current weather for the configured city.
     *
     * Returns null (rather than throwing) on any failure — missing key,
     * network error, invalid city, rate limit — so a weather outage never
     * breaks the dashboard for something unrelated like ringing up a sale.
     */
    public function current(): ?array
    {
        $key = config('services.openweather.key');
        $city = config('services.openweather.city');
        $units = config('services.openweather.units');

        if (! $key) {
            return null;
        }

        $cacheKey = "weather.current.{$city}.{$units}";

        return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_MINUTES), function () use ($key, $city, $units) {
            try {
                $response = Http::timeout(5)->get(self::ENDPOINT, [
                    'q' => $city,
                    'appid' => $key,
                    'units' => $units,
                ]);

                if (! $response->successful()) {
                    Log::warning('OpenWeather API request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return null;
                }

                $data = $response->json();

                return [
                    'city' => $data['name'] ?? $city,
                    'temp' => round($data['main']['temp'] ?? 0),
                    'feels_like' => round($data['main']['feels_like'] ?? 0),
                    'humidity' => $data['main']['humidity'] ?? null,
                    'description' => ucfirst($data['weather'][0]['description'] ?? ''),
                    'icon' => $data['weather'][0]['icon'] ?? null,
                    'wind_speed' => $data['wind']['speed'] ?? null,
                    'units' => $units,
                ];
            } catch (\Throwable $e) {
                Log::warning('OpenWeather API exception: '.$e->getMessage());

                return null;
            }
        });
    }
}

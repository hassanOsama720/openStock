<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Lang
{
    public function handle($request, Closure $next)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $locale = $this->parseLocale($acceptLanguage);

        if ($locale) {
            App::setLocale($locale); // Set the locale for the application
        }

        return $next($request);
    }

    private function parseLocale($acceptLanguage): array|string|null
    {
        // Split by commas and extract the first language code
        $locales = explode(',', $acceptLanguage);
        foreach ($locales as $lang) {
            $locale = explode(';', $lang)[0]; // Get the main locale
            $locale = str_replace('-', '_', trim($locale)); // Replace hyphen with underscore

            if (preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $locale)) {
                return $locale; // Return the first valid locale
            }
        }

        return null; // Default to null if no valid locale is found
    }
}


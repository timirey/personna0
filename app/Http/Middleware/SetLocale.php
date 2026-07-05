<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Validates the {locale} route segment, sets the application locale, and
     * persists the choice in a long-lived cookie so the root "/" redirect can
     * remember it on the next visit.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! in_array($locale, config('locales.supported'), true)) {
            abort(404);
        }

        app()->setLocale($locale);

        $response = $next($request);

        return $response->withCookie(cookie()->forever('locale', $locale));
    }
}

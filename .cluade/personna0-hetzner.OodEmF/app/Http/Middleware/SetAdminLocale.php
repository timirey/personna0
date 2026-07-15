<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    /**
     * Sets the admin panel UI language from the `admin_locale` cookie,
     * defaulting to Russian. Independent from the storefront locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('admin_locale');

        if (! in_array($locale, config('locales.supported'), true)) {
            $locale = 'ru';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

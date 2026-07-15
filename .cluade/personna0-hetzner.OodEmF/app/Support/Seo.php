<?php

namespace App\Support;

class Seo
{
    /**
     * The current page's URL in every supported locale, keyed by locale.
     * Built by swapping the {locale} segment of the current route and
     * preserving the query string (minus locale).
     */
    public static function alternates(): array
    {
        $route = request()->route();
        $name = $route?->getName();

        if (! $name) {
            return [];
        }

        $params = $route->parameters();
        $query = request()->query();
        unset($query['locale']);

        $alternates = [];
        foreach (config('locales.supported') as $locale) {
            $url = route($name, array_merge($params, ['locale' => $locale]));
            if (! empty($query)) {
                $url .= '?'.http_build_query($query);
            }
            $alternates[$locale] = $url;
        }

        return $alternates;
    }

    public static function canonical(): string
    {
        return static::alternates()[app()->getLocale()] ?? url()->current();
    }

    public static function xDefault(): string
    {
        return static::alternates()[config('locales.default')] ?? url()->current();
    }
}

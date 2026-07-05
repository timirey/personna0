<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', now()->addHour(), function () {
            $locales = config('locales.supported');

            $entries = [['route' => 'catalogue', 'params' => [], 'lastmod' => null, 'priority' => '1.0']];

            foreach (Product::query()->active()->get(['id', 'slug', 'updated_at']) as $product) {
                $entries[] = [
                    'route' => 'product',
                    'params' => ['slug' => $product->slug],
                    'lastmod' => $product->updated_at,
                    'priority' => '0.8',
                ];
            }

            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

            foreach ($entries as $entry) {
                foreach ($locales as $locale) {
                    $out .= "  <url>\n";
                    $out .= '    <loc>'.e(route($entry['route'], array_merge(['locale' => $locale], $entry['params']))).'</loc>'."\n";
                    foreach ($locales as $alt) {
                        $href = route($entry['route'], array_merge(['locale' => $alt], $entry['params']));
                        $out .= '    <xhtml:link rel="alternate" hreflang="'.$alt.'" href="'.e($href).'"/>'."\n";
                    }
                    if ($entry['lastmod']) {
                        $out .= '    <lastmod>'.$entry['lastmod']->toAtomString().'</lastmod>'."\n";
                    }
                    $out .= '    <priority>'.$entry['priority'].'</priority>'."\n";
                    $out .= "  </url>\n";
                }
            }

            return $out.'</urlset>'."\n";
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /*/cart',
            'Disallow: /*/checkout',
            'Disallow: /*/order/',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain']);
    }
}

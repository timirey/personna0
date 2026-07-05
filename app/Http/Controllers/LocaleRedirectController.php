<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LocaleRedirectController extends Controller
{
    /** Root entry point: detect the best locale and redirect to /{locale}. */
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect('/'.$this->detect($request));
    }

    private function detect(Request $request): string
    {
        $supported = config('locales.supported');

        $cookie = $request->cookie('locale');
        if (in_array($cookie, $supported, true)) {
            return $cookie;
        }

        $preferred = $request->getPreferredLanguage($supported);

        return in_array($preferred, $supported, true)
            ? $preferred
            : config('locales.default');
    }
}

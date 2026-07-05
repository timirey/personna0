<?php

namespace App\Support;

use App\Settings\ShopSettings;

class Money
{
    /** Format an amount as "<number> <CURRENCY>", e.g. "350.00 MDL". */
    public static function format(float|int|string $amount): string
    {
        return number_format((float) $amount, 2).' '.static::currency();
    }

    public static function currency(): string
    {
        return app(ShopSettings::class)->currency;
    }
}

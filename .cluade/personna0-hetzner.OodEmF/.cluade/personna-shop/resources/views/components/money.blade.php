@props(['amount'])
{{ number_format((float) $amount, 2) }} {{ config('shop.currency') }}

@props(['alternates' => [], 'current' => null])
@php $current ??= app()->getLocale(); @endphp
<div class="lang-switcher" role="group" aria-label="{{ __('shop.language') }}">
    @foreach ($alternates as $loc => $url)
        <a href="{{ $url }}" hreflang="{{ $loc }}" rel="alternate" data-locale-switch
           @class(['is-active' => $loc === $current])
           @if ($loc === $current) aria-current="true" @endif>{{ strtoupper($loc) }}</a>
    @endforeach
</div>

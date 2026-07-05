@props(['amount' => 0])
<span {{ $attributes->merge(['class' => 'money']) }}>{{ \App\Support\Money::format($amount) }}</span>

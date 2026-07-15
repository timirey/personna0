<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotifier
{
    public function sendNewOrder(Order $order): void
    {
        $token  = config('shop.telegram_token');
        $chatId = config('shop.telegram_chat_id');

        if (blank($token) || blank($chatId)) {
            return; // not configured yet
        }

        $order->loadMissing('items');
        $currency = config('shop.currency');

        $lines   = [];
        $lines[] = '🧾 <b>New order ' . e($order->reference) . '</b>';
        $lines[] = '';

        foreach ($order->items as $item) {
            $size    = $item->size ? ' · ' . e($item->size) : '';
            $lines[] = '• ' . e($item->product_name) . $size
                . ' × ' . $item->qty
                . ' — ' . number_format((float) $item->line_total, 2) . ' ' . $currency;
        }

        $lines[] = '';
        $lines[] = '<b>Total: ' . number_format((float) $order->total, 2) . ' ' . $currency . '</b>';
        $lines[] = '';
        $lines[] = '👤 ' . e($order->customer_name);
        $lines[] = '📞 ' . e($order->customer_phone);

        if (filled($order->customer_email)) {
            $lines[] = '✉️ ' . e($order->customer_email);
        }
        if (filled($order->address)) {
            $lines[] = '📦 ' . e($order->address);
        }
        if (filled($order->notes)) {
            $lines[] = '📝 ' . e($order->notes);
        }

        $text = implode("\n", $lines);

        try {
            $response = Http::asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'                  => $chatId,
                'text'                     => $text,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if ($response->failed()) {
                Log::warning('Telegram order notify failed', [
                    'reference' => $order->reference,
                    'body'      => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Telegram order notify error: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Services;

use App\Models\Order;
use App\Settings\ShopSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotifier
{
    public function __construct(private ShopSettings $settings) {}

    public function sendNewOrder(Order $order): void
    {
        if (! $this->settings->telegramEnabled()) {
            return; // not configured — order still saves
        }

        $order->loadMissing('items');
        $currency = $this->settings->currency;

        $lines = [];
        $lines[] = '🧾 <b>New order '.e($order->reference).'</b>';
        $lines[] = '';

        foreach ($order->items as $item) {
            $size = $item->size ? ' · '.e($item->size) : '';
            $lines[] = '• '.e($item->product_name).$size
                .' × '.$item->qty
                .' — '.number_format((float) $item->line_total, 2).' '.$currency;
        }

        $lines[] = '';
        $lines[] = '<b>Total: '.number_format((float) $order->total, 2).' '.$currency.'</b>';
        $lines[] = '';
        $lines[] = '👤 '.e($order->customer_name);
        $lines[] = '📞 '.e($order->customer_phone);

        $text = implode("\n", $lines);

        try {
            $response = Http::asForm()->post(
                "https://api.telegram.org/bot{$this->settings->telegram_bot_token}/sendMessage",
                [
                    'chat_id' => $this->settings->telegram_chat_id,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]
            );

            if ($response->failed()) {
                Log::warning('Telegram order notify failed', [
                    'reference' => $order->reference,
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Telegram order notify error: '.$e->getMessage());
        }
    }
}

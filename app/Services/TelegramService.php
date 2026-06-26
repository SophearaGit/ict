<?php
// app/Services/TelegramService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', '');
        $this->chatId = config('services.telegram.chat_id', '');
    }

    public function send(string $message, ?string $chatId = null, ?string $botToken = null): bool
    {
        $token = $botToken ?? $this->botToken;
        $target = $chatId ?? $this->chatId;

        if (empty($token) || empty($target)) {
            Log::warning('Telegram: bot_token or chat_id not configured.');
            return false;
        }

        try {
            $response = Http::timeout(10)->post(
                "https://api.telegram.org/bot{$token}/sendMessage",
                [
                    'chat_id' => $target,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                ]
            );

            if (!$response->successful()) {
                Log::error('Telegram send failed', ['body' => $response->body()]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Telegram send exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}

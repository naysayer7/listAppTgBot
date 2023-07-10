<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private $api_url = 'https://api.telegram.org/bot';

    public function __construct(private string $api_token)
    {
    }

    public function setMyCommands(array $commands): Response
    {
        $url = $this->api_url . $this->api_token . '/setMyCommands';

        return Http::post($url, ['commands' => $commands,]);
    }

    public function deleteMyCommands(): Response
    {
        $url = $this->api_url . $this->api_token . '/deleteMyCommands';
        return Http::get($url);
    }

    public function sendMessage(string $text, Chat $chat): Response
    {
        $url = $this->api_url . $this->api_token . '/sendMessage';

        return Http::post($url, [
            'text' => $text,
            'chat_id' => $chat->id,
        ]);
    }
}

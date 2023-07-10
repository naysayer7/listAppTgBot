<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Services\ListService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramController extends Controller
{
    public function __construct(
        protected TelegramService $telegram,
        protected ListService $list
    ) {
    }

    public function inbound(Request $request)
    {
        // If incoming update is a message
        if ($request->message) {
            $this->message($request);
            return;
        }
    }

    protected function message(Request $request)
    {
        $user_id = $request->message['from']['id'];
        $chat = Chat::find($user_id);
        $text = $request->message['text'];



        if (!$chat) {
            $chat = Chat::create(['id' => $user_id, 'token' => $this->generateToken($user_id)]);
        }

        if ($text == '/token') {
            $this->sendToken($chat);
            return;
        }

        if ($text == '/get') {
            $this->showList($chat);
            return;
        }

        if ($text == '/newtoken') {
            $this->newToken($chat);
            return;
        }
    }

    protected function showList(Chat $chat)
    {
        $response = $this->list->fetch($chat->token);
        if ($response->unauthorized()) {
            $this->telegram->sendMessage('Токен не зарегистрирован', $chat);
            return;
        }
        if ($response->failed()) {
            $this->telegram->sendMessage("Ошибка {$response->status()}", $chat);
            return;
        }

        $data = $response->json();

        $message = '';
        foreach ($data as $item) {
            $message .= '-  ' . $item['body'] . "\n\n";
        }

        // If no items
        if (!$message)
            $message = 'Пусто';

        $this->telegram->sendMessage($message, $chat);
    }

    protected function newToken(Chat $chat)
    {
        $chat->token = $this->generateToken($chat->id);
        $chat->save();
        $this->sendToken($chat);
    }

    protected function sendToken(Chat $chat)
    {
        $this->telegram->sendMessage($chat->token, $chat);
    }

    protected function generateToken(int $user_id)
    {
        return $user_id . ':' . Str::random(40);
    }
}

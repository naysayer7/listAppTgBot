<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\Response;

class ListService
{
    public function __construct(private string $backend_url)
    {
    }

    public function fetch(string $token): Response
    {
        $url = $this->backend_url . "/api/items";
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        return Http::withHeaders($headers)->get($url);
    }
}

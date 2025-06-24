<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class FastApiClient
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('FASTAPI_URL', 'http://127.0.0.1:8000');
    }

    public function getAllVideos()
    {
        $response = Http::get("{$this->baseUrl}/videos");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Erreur de connexion à FastAPI");
    }
}
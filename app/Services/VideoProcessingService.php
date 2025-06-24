<?php

namespace App\Services;

use GuzzleHttp\Client;

class VideoProcessingService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('FASTAPI_URL', 'http://fastapi-service:8000'),
            'timeout'  => 300,
        ]);
    }

    /**
     * Upload une vidéo vers FastAPI et récupère les URLs DRM
     */
    public function uploadVideo(string $filePath)
    {
        $response = $this->client->request('POST', '/upload/', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Erreur lors de l’upload vidéo vers FastAPI');
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return $data; // ['video_id' => ..., 'manifest_url' => ..., 'hls_url' => ...]
    }
}
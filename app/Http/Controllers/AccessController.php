<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Firebase\JWT\JWT;

class AccessController extends Controller
{
    public function getAccess(Video $video)
    {
        $payload = [
            'sub' => auth()->id(),
            'video_id' => $video->id,
            'exp' => now()->addMinutes(60)->timestamp
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'token' => $token,
            'manifest_url' => asset($video->manifest_url) . "?token=$token",
            'license_url' => $video->license_url
        ]);
    }
}
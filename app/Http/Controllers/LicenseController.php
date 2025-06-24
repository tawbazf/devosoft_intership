<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Video;

class LicenseController extends Controller
{
    public function requestLicense(Video $video)
    {
        $response = Http::post('https://license.example.com/api/licenses', [
            'video_id' => $video->id,
            'user_id' => Auth::id(),
        ]);

        if ($response->ok()) {
            $video->update(['license_url' => $response->json()['license_url']]);
            return response()->json(['message' => 'Licence créée']);
        }

        return response()->json(['error' => 'Erreur DRM'], 500);
    }
}
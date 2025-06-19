<?php
namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Jobs\PackageVideo;
use Firebase\JWT\JWT;

class VideoController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'video_file' => 'required|file|mimes:mp4',
        ]);

        $path = $request->file('video_file')->store('videos', 'public');

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'path' => $path,
            'status' => 'pending'
        ]);

        dispatch(new PackageVideo($video));

        return response()->json(['message' => 'Vidéo en cours de traitement']);
    }
    public function show(Video $video) {
    $token = JWT::encode([
        'sub' => auth()->id(),
        'video_id' => $video->id,
        'exp' => now()->addMinutes(60)->timestamp
    ], env('JWT_SECRET'), 'HS256');

    $manifest_url = asset($video->manifest_url) . '?token=' . $token;

    return view('player', compact('video', 'manifest_url'));
}
}
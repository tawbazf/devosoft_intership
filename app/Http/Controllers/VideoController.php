<?php
namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Jobs\PackageVideo;

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
}
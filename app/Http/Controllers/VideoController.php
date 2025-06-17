<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

use App\Models\Video;
use Aws\S3\S3Client;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Video::query();
        if (!$request->user()->is_admin) {
            $query->where('user_id', $request->user()->id);
        }
        if ($request->has('format')) {
            $query->where('format', $request->query('format'));
        }
        if ($request->has('drm')) {
            $query->where('drm', $request->query('drm'));
        }

        return response()->json(['videos' => $query->get()->map(fn($video) => [
            'id' => $video->id,
            'filename' => $video->filename,
            'format' => $video->format,
            'drm' => $video->drm,
            'uploaded_at' => $video->uploaded_at,
            'user_id' => $video->user_id,
        ])]);
    }

    public function show(Video $video, Request $request)
    {
        if (!$request->user()->is_admin && $video->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['video' => [
            'id' => $video->id,
            'filename' => $video->filename,
            'format' => $video->format,
            'drm' => $video->drm,
            'uploaded_at' => $video->uploaded_at,
            'user_id' => $video->user_id,
        ]]);
    }

    public function destroy(Video $video, Request $request)
    {
        if (!$request->user()->is_admin && $video->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Delete video from S3
        $s3->deleteObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => "videos/{$video->id}/encrypted_{$video->format}_{$video->drm}." . ($video->format === 'hls' ? 'm3u8' : 'mpd'),
        ]);

        $video->delete();
        return response()->json(['message' => 'Video deleted']);
    }

    public function getManifest(Request $request, $videoId)
    {
        $format = $request->query('format', 'dash');
        $drm = $request->query('drm', 'widevine');

        $video = Video::where('id', $videoId)
            ->where('format', $format)
            ->where('drm', $drm)
            ->first();

        if (!$video || (!$request->user()->is_admin && $video->user_id !== $request->user()->id)) {
            return response()->json(['error' => 'Video not found or unauthorized'], 404);
        }

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $signedUrl = $s3->createPresignedRequest(
            $s3->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => "videos/{$videoId}/encrypted_{$format}_{$drm}." . ($format === 'hls' ? 'm3u8' : 'mpd'),
            ]),
            '+5 minutes'
        )->getUri();

        return response()->json(['manifest_url' => (string) $signedUrl]);
    }
}
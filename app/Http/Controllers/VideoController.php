<?php

namespace App\Http\Controllers;

use App\Services\FastApiClient;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Jobs\PackageVideo;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use App\Services\VideoProcessingService;

class VideoController extends Controller
{
    protected $api;
    protected $videoProcessor;

    public function __construct(FastApiClient $api, VideoProcessingService $videoProcessor)
    {
        $this->api = $api;
        $this->videoProcessor = $videoProcessor;
    }

    /**
     * Affiche la liste des vidéos depuis FastAPI
     */
    public function index()
    {
        $videos = $this->api->getAllVideos();
        return view('videos.index', compact('videos'));
    }

    /**
     * Enregistre une vidéo localement et lance le job Laravel (non FastAPI)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'video_file' => 'required|file|mimes:mp4',
        ]);

        $path = $request->file('video_file')->store('videos', 'public');

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description ?? '',
            'path' => $path,
            'status' => 'pending',
        ]);

        dispatch(new PackageVideo($video));

        return response()->json(['message' => 'Vidéo en cours de traitement']);
    }

    /**
     * Affiche un lecteur vidéo avec manifest protégé par JWT
     */
    public function show(Video $video)
    {
        $token = JWT::encode([
            'sub' => Auth::id(),
            'video_id' => $video->id,
            'exp' => now()->addMinutes(60)->timestamp,
        ], env('JWT_SECRET'), 'HS256');

        $manifest_url = asset($video->manifest_url) . '?token=' . $token;

        return view('player', compact('video', 'manifest_url'));
    }

    /**
     * Upload vidéo et appelle FastAPI (traitement + packaging)
     */
    public function upload(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime',
        ]);

        $path = $request->file('video')->store('uploads');

        // Appelle FastAPI (via VideoProcessingService)
        $result = $this->videoProcessor->uploadVideo(storage_path('app/' . $path));

        // Crée la vidéo dans la base Laravel
        $video = Video::create([
            'title' => $request->input('title', 'Sans titre'),
            'manifest_url' => $result['manifest_url'],
            'license_url' => '', // si tu gères une URL DRM externe
            'hls_url' => $result['hls_url'] ?? null,
            'path' => $path,
        ]);

        return response()->json([
            'message' => 'Vidéo uploadée et traitée avec succès',
            'video' => $video,
        ]);
    }
}
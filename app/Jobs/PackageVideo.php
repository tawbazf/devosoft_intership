<?php

namespace App\Jobs;



use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use Illuminate\Support\Str;

class PackageVideo implements ShouldQueue
{
    use Queueable;

    public function __construct(public Video $video) {}

    public function handle()
    {
        $inputPath = storage_path('app/public/' . $this->video->path);
        $outputDir = storage_path('app/public/packaged/' . $this->video->id);
        if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

        // Exécution FFmpeg (720p)
        $ffmpegCmd = "ffmpeg -i $inputPath -c:v libx264 -crf 23 -preset fast -c:a aac $outputDir/output.mp4";
        shell_exec($ffmpegCmd);

        // DRM avec Shaka Packager (Widevine)
        $contentId = Str::uuid();
        $packagerCmd = "
        packager 
        input=$outputDir/output.mp4,stream=video,output=$outputDir/video_encrypted.mp4 
        --enable_widevine_encryption 
        --key_server_url https://license.example.com 
        --content_id $contentId 
        --signer signer_id 
        --aes_signing_key abcdef1234567890 
        --aes_signing_iv 0123456789abcdef 
        --mpd_output $outputDir/manifest.mpd";

        shell_exec($packagerCmd);

        $this->video->update([
            'manifest_url' => 'storage/packaged/' . $this->video->id . '/manifest.mpd',
            'license_url' => 'https://license.example.com',
            'status' => 'packaged'
        ]);
    }
}
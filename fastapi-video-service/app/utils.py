import os, uuid, shutil, subprocess
from fastapi import UploadFile
from pathlib import Path

UPLOAD_DIR = "uploads"
OUTPUT_DIR = "outputs"
os.makedirs(UPLOAD_DIR, exist_ok=True)
os.makedirs(OUTPUT_DIR, exist_ok=True)

async def process_video(file: UploadFile):
    video_id = str(uuid.uuid4())
    upload_path = os.path.join(UPLOAD_DIR, f"{video_id}.mp4")

    # Sauvegarde temporaire
    with open(upload_path, "wb") as buffer:
        shutil.copyfileobj(file.file, buffer)

    encoded_path = os.path.join(OUTPUT_DIR, f"{video_id}_encoded.mp4")

    # Encodage FFmpeg
    ffmpeg_cmd = [
        "ffmpeg", "-i", upload_path,
        "-c:v", "libx264", "-crf", "23", "-preset", "fast",
        "-c:a", "aac", "-b:a", "128k",
        encoded_path
    ]
    subprocess.run(ffmpeg_cmd, check=True)

    # Packaging DRM avec Shaka Packager
    packaged_dir = os.path.join(OUTPUT_DIR, f"{video_id}_packaged")
    os.makedirs(packaged_dir, exist_ok=True)

    mpd_path = os.path.join(packaged_dir, "manifest.mpd")
    hls_path = os.path.join(packaged_dir, "playlist.m3u8")

    # ⚠️ À remplacer par tes vraies clés Widevine
    packager_cmd = [
        "packager",
        f"in={encoded_path},stream=video,output={packaged_dir}/video.mp4",
        f"in={encoded_path},stream=audio,output={packaged_dir}/audio.mp4",
        "--enable_widevine_encryption",
        "--key_id=0123456789abcdef0123456789abcdef",
        "--key=0123456789abcdef0123456789abcdef",
        "--pssh=00000000000000000000000000000000",
        "--mpd_output", mpd_path,
        "--hls_master_playlist_output", hls_path
    ]
    subprocess.run(packager_cmd, check=True)

    return {
        "video_id": video_id,
        "manifest_url": f"/static/{video_id}_packaged/manifest.mpd",
        "hls_url": f"/static/{video_id}_packaged/playlist.m3u8"
    }

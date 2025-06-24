from fastapi import FastAPI, File, UploadFile, HTTPException
import shutil
import os
import subprocess
import uuid
import boto3

app = FastAPI()

# Config AWS S3 (à remplacer par tes infos)
S3_BUCKET = "ton-bucket"
AWS_ACCESS_KEY = "xxx"
AWS_SECRET_KEY = "yyy"
AWS_REGION = "us-east-1"

s3_client = boto3.client(
    "s3",
    aws_access_key_id=AWS_ACCESS_KEY,
    aws_secret_access_key=AWS_SECRET_KEY,
    region_name=AWS_REGION
)

UPLOAD_DIR = "./uploads"
os.makedirs(UPLOAD_DIR, exist_ok=True)


@app.post("/upload/")
async def upload_video(file: UploadFile = File(...)):
    # Sauvegarder la vidéo uploadée
    video_id = str(uuid.uuid4())
    upload_path = os.path.join(UPLOAD_DIR, f"{video_id}.mp4")

    with open(upload_path, "wb") as buffer:
        shutil.copyfileobj(file.file, buffer)

    # Encoder avec FFmpeg (exemple simple)
    encoded_path = os.path.join(UPLOAD_DIR, f"{video_id}_encoded.mp4")
    ffmpeg_cmd = [
        "ffmpeg", "-i", upload_path,
        "-c:v", "libx264", "-crf", "23", "-preset", "fast",
        "-c:a", "aac", "-b:a", "128k",
        encoded_path
    ]
    subprocess.run(ffmpeg_cmd, check=True)

    # Packaging DRM avec Shaka Packager (Widevine + FairPlay)
    packaged_dir = os.path.join(UPLOAD_DIR, f"{video_id}_packaged")
    os.makedirs(packaged_dir, exist_ok=True)
    mpd_path = os.path.join(packaged_dir, "manifest.mpd")
    hls_path = os.path.join(packaged_dir, "playlist.m3u8")

    # NOTE: Remplace <KEY_ID>, <KEY>, <PSSH> par tes valeurs DRM
    packager_cmd = [
        "packager",
        f"in={encoded_path},stream=video,output={packaged_dir}/video.mp4",
        f"in={encoded_path},stream=audio,output={packaged_dir}/audio.mp4",
        "--enable_widevine_encryption",
        "--key_id=<KEY_ID>",
        "--key=<KEY_HEX>",
        "--pssh=<WIDEVINE_PSSH>",
        "--mpd_output", mpd_path,
        "--hls_master_playlist_output", hls_path
    ]
    subprocess.run(packager_cmd, check=True)

    # Upload vers S3
    s3_client.upload_file(mpd_path, S3_BUCKET, f"{video_id}/manifest.mpd")
    s3_client.upload_file(hls_path, S3_BUCKET, f"{video_id}/playlist.m3u8")

    manifest_url = f"https://{S3_BUCKET}.s3.amazonaws.com/{video_id}/manifest.mpd"
    hls_url = f"https://{S3_BUCKET}.s3.amazonaws.com/{video_id}/playlist.m3u8"

    return {
        "video_id": video_id,
        "manifest_url": manifest_url,
        "hls_url": hls_url
    }

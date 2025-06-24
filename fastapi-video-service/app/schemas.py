from pydantic import BaseModel
from datetime import datetime

class VideoCreate(BaseModel):
    id: str
    title: str
    manifest_url: str
    hls_url: str

class VideoOut(VideoCreate):
    created_at: datetime

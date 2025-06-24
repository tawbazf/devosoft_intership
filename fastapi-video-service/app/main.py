from fastapi import FastAPI, UploadFile, File
from app.utils import process_video

app = FastAPI()

@app.get("/")
def home():
    return {"message": "Service FastAPI opérationnel."}

@app.post("/upload/")
async def upload_video(file: UploadFile = File(...)):
    result = await process_video(file)
    return result
from fastapi.staticfiles import StaticFiles

app.mount("/static", StaticFiles(directory="outputs"), name="static")

from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker

DATABASE_URL = "postgresql://postgres:tawba@localhost:5432/video"

engine = create_engine(DATABASE_URL)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
from fastapi import Depends
from sqlalchemy.orm import Session
from main import SessionLocal, engine
from app import models

# Crée les tables
models.Base.metadata.create_all(bind=engine)

# Dépendance pour avoir une session
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
from app import schemas, models
from sqlalchemy.orm import Session

@app.post("/upload/", response_model=schemas.VideoOut)
async def upload_video(file: UploadFile = File(...), db: Session = Depends(get_db)):
    result = await process_video(file)
    
    video = models.Video(
        id=result["video_id"],
        title="Video auto",
        manifest_url=result["manifest_url"],
        hls_url=result["hls_url"]
    )
    db.add(video)
    db.commit()
    db.refresh(video)
    return video
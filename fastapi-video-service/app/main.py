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

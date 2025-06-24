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

from sqlalchemy import Column, String, DateTime
from sqlalchemy.ext.declarative import declarative_base
from datetime import datetime

Base = declarative_base()

class Video(Base):
    __tablename__ = "videos"

    id = Column(String, primary_key=True)
    title = Column(String, nullable=False)
    manifest_url = Column(String)
    hls_url = Column(String)
    created_at = Column(DateTime, default=datetime.utcnow)

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Video extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'filename', 'format', 'drm', 'uploaded_at', 'user_id'];
    protected $casts = [
        'format' => 'string',
        'drm' => 'string',
        'uploaded_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
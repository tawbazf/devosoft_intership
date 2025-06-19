<?php
 namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Video extends Model
{
use HasFactory;

public $incrementing = false;
protected $keyType = 'string';

protected $fillable = [
'id',
'user_id',
'title',
'description',
'filename',
'path',
'format',
'drm',
'manifest_url',
'license_url',
'status',
'views',
'uploaded_at',
];

protected static function booted() {
static::creating(function ($video) {
if (!$video->id) {
$video->id = (string) Str::uuid();
}
});
}

public function user() {
return $this->belongsTo(User::class);
}
}
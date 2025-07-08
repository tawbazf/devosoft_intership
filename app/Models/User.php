<?php
 namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\JWTException;

class User extends Authenticatable implements JWTSubject
{
use Notifiable;

protected $fillable = [
'email',
'password',
'role',
];

protected $hidden = [
'password',
'remember_token',
];

// JWT
public function getJWTIdentifier() {
return $this->getKey();
}

public function getJWTCustomClaims(): array {
return [];
}

public function videos() {
return $this->hasMany(Video::class);
}

public function isAdmin() {
return $this->role === 'admin';
}
}
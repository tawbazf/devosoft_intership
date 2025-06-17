<?php
 namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
protected $fillable = ['email', 'password', 'is_admin'];
protected $casts = ['is_admin' => 'boolean'];
protected $hidden = ['password'];
}
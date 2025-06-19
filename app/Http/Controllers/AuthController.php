<?php
 namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
public function login(Request $request) {
$credentials = $request->only('email', 'password');

if (!$token = JWTAuth::attempt($credentials)) {
return response()->json(['error' => 'Unauthorized'], 401);
}

return response()->json(['token' => $token]);
}

public function register(Request $request) {
$user = User::create([
'name' => $request->name,
'email' => $request->email,
'password' => bcrypt($request->password),
'role' => 'viewer'
]);

$token = JWTAuth::fromUser($user);
return response()->json(['token' => $token]);
}

public function me() {
return response()->json(auth()->user());
}
}
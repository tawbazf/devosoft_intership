<?php
 namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class Authenticate
{
public function handle(Request $request, Closure $next)
{
$token = $request->bearerToken();
if (!$token) {
return response()->json(['error' => 'Unauthorized'], 401);
}

$config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(env('JWT_SECRET')));
try {
$parsedToken = $config->parser()->parse($token);
$config->validator()->assert($parsedToken, new SignedWith($config->signer(), $config->signingKey()));
$userId = $parsedToken->claims()->get('sub');
$user = \App\Models\User::findOrFail($userId);
\Auth::setUser($user);
} catch (\Exception $e) {
return response()->json(['error' => 'Invalid token'], 401);
}

return $next($request);
}
}
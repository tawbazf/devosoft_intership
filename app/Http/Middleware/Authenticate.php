<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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

        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(env('JWT_SECRET'))
        );

        try {
            $parsedToken = $config->parser()->parse($token);
            $config->validator()->assert(
                $parsedToken,
                new SignedWith($config->signer(), $config->signingKey())
            );

            $userId = $parsedToken->claims()->get('sub');

            // Assurez-vous que c'est un entier
            if (!is_numeric($userId)) {
                throw new \Exception('Invalid user ID in token');
            }

            $user = User::findOrFail((int) $userId);

            Auth::setUser($user);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
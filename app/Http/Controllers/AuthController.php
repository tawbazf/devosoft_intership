<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use DateTimeImmutable;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(env('JWT_SECRET')));
        $token = $config->builder()
            ->issuedBy(env('APP_URL'))
            ->permittedFor('client')
            ->relatedTo($user->id)
            ->expiresAt((new DateTimeImmutable())->modify('+1 hour'))
            ->getToken($config->signer(), $config->signingKey());

        return response()->json([
            'access_token' => $token->toString(),
            'token_type' => 'bearer',
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'is_admin' => 'boolean'
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => $data['is_admin'] ?? false,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
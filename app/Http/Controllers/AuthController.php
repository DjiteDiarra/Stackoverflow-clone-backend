<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation des données d'inscription
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Création d'un nouvel utilisateur
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            // Logue de débogage
            \Log::info('User enregistré avec succès', ['user' => $user]);

            // return response()->json([
            //     'message' => 'User saved successfully',
            //     'code' => 200
            // ]);
            return response()->json(['success' => true, 'message' => 'User registered successfully'], 201);
            
        } catch (Exception $e) {
            // Logue de l'erreur
            \Log::error('Erreur lors de l\'enregistrement de l\'utilisateur', ['error' => $e->getMessage()]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Révoquer les anciens tokens
            $user->tokens()->delete();

            // Créer un nouveau token
            $tokenResult = $user->createToken('Personal Access Token');
            $accessToken = $tokenResult->plainTextToken;
            $expiresAt = $tokenResult->accessToken->expires_at ?? now()->addMinutes(config('sanctum.expiration'));

            $tokenExpiration = $expiresAt->diffInMinutes(now());

            return response()->json([
                'access_token' => $accessToken,
                'expires_in' => $tokenExpiration,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required',
        ]);

        $user = Auth::guard('sanctum')->user();

        // Révoquer l'ancien token et en créer un nouveau
        $user->tokens()->delete();
        $tokenResult = $user->createToken('Personal Access Token');
        $accessToken = $tokenResult->plainTextToken;
        $expiresAt = $tokenResult->accessToken->expires_at ?? now()->addMinutes(config('sanctum.expiration'));

        $tokenExpiration = $expiresAt->diffInMinutes(now());

        return response()->json([
            'access_token' => $accessToken,
            'expires_in' => $tokenExpiration,
        ]);
    }

    public function user(Request $request)
    {
        // Retourne les informations de l'utilisateur authentifié
        return response()->json($request->user());
    }
}


 



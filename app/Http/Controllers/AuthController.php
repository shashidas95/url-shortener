<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        if (!Auth::attempt($validated)) {
            return response()->json([
                "message" => "Login Credentials arent correct",
            ], 401);
        }
        $user = User::where("email", $validated['email'])->first();
        return response()->json([
            "access_token"=> $user->createToken('api_token')->plainTextToken,
            "token_type"=>"Bearer",
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            "email"=> "required|email",
            "password"=> "required"
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                "email"=> ['Incorrect Credentials']
            ]);
        }

        if(!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email'=> ['Incorreect Credentials']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message'=> 'Logged Out!!!'
        ]);
    }
}

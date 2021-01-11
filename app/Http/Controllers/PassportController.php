<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    public function register(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        
        if($validatedData) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('EnlightureAcademy')->accessToken;

            return response()->json(['token' => $token], 200);
        }else{
            return response()->json([
                'error' => 'Data failed to validate'
            ], 400);
        }
    }

    public function login(Request $request) {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('EnlightureAcademy')->accessToken;
            return response()->json(['token' => $token], 200);
        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request) {
        $request->user()->token()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function details() {
        return response()->json(['user' => Auth::user()], 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();
            $token = $user->createToken('app')->plainTextToken;
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user
            ]);
        }
        return response()->json([
            'message' => 'Неверно указан логин или пароль'
        ], 401);
    }
}

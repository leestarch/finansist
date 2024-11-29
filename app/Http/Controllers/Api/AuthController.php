<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

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



    /* Авторизация для админки*/
    public function adminAuth(Request $request)
    {
        $user_id = request('user_id');
        if (!app()->isLocal()) {
            if (\request('token') != config('app.key')) {
                return redirect()->route('ditLogin',
                    [
                        'user_id' => $user_id,
                    ]
                );
            }
        } else
            $user_id = 1;

        Cookie::queue(Cookie::make('user_id', $user_id, 600));

        return redirect()->route('home');
    }

    public function ditLogin()
    {
        $params = [
            'user_id' => request('user_id'),
            'token' => config('app.key'),
            'url' => route('adminAuth')
        ];

        return redirect()->to('https://dyatlovait.ru/service/mms?' . http_build_query($params));
    }
}

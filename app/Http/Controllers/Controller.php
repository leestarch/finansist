<?php

namespace App\Http\Controllers;

use App\Models\Prop;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function vue()
    {
        $user_id = Cookie::get('user_id', 1);

        $params =[
            'user_id' => $user_id,
        ];

        $params = json_encode($params);
        return view('home', compact('params'));
    }

}

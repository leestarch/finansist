<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PizzeriaResource;
use App\Models\Pizzeria;

class PizzeriaController extends Controller
{

    public function index()
    {
        return PizzeriaResource::collection(Pizzeria::all());
    }
}

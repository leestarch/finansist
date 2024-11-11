<?php

namespace App\Http\Controllers;

use App\Models\Pizzeria;
use App\Services\CategoryService;

class CategoriesController extends Controller
{
    public function index()
    {
        $startDate = \request()->get('startDate');
        $endDate = \request()->get('endDate');
        $groupBy = \request()->get('groupBy', 'daily');

        $categories = CategoryService::getCategoryTree(true, $startDate, $endDate, $groupBy);
        $pizzerias = Pizzeria::query()->select('id', 'name')->get();

        return response()->json([
           'categories' => $categories,
           'pizzerias' => $pizzerias,
        ]);
    }
}

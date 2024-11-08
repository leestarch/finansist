<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;

class CategoriesController extends Controller
{
    public function index()
    {
        $startDate = \request()->get('startDate');
        $endDate = \request()->get('endDate');
        $groupBy = \request()->get('groupBy', 'daily');

        $categories = CategoryService::getCategoryTree(true, $startDate, $endDate, $groupBy);
        return response()->json([
           'categories' => $categories
        ]);
    }
}

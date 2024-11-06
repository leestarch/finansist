<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $startDate = \request()->get('startDate');
        $endDate = \request()->get('endDate');
        $groupBy = \request()->get('groupBy', 'daily');

        $categories = Category::getCategoryTree(true, $startDate, $endDate, $groupBy);
        return response()->json([
           'categories' => $categories
        ]);
    }
}

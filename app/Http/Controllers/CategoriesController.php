<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\Pizzeria;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use function request;

class CategoriesController extends Controller
{
    public function index(): JsonResponse
    {
        $startDate = request()->get('startDate');
        $endDate = request()->get('endDate');
        $groupBy = request()->get('groupBy', 'daily');
        $pizzeriaId = request()->get('pizzeriaId');
        $contractorIds = request()->get('contractorIds');

        $categories = CategoryService::getCategoryTree(
            true, $startDate, $endDate, $groupBy, $pizzeriaId, $contractorIds
        );
        $pizzerias = Pizzeria::query()->select('id', 'name')->get();

        return response()->json([
           'categories' => $categories,
           'pizzerias' => $pizzerias,
        ]);
    }
}

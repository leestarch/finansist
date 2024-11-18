<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\MinifiedCategoryResource;
use App\Models\Category;
use App\Models\Pizzeria;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use function request;

class CategoriesController extends Controller
{
    public function indexTree(): JsonResponse
    {
        $startDate = request()->get('startDate');
        $endDate = request()->get('endDate');
        $groupBy = request()->get('groupBy', 'daily');
        $pizzeriaId = request()->get('pizzeriaId');
        $contractorIds = request()->get('contractorIds');
        $purposeQuery = request()->get('purposeQuery');

        $categories = CategoryService::getCategoryTree(
            true, $startDate, $endDate, $groupBy, $pizzeriaId, $contractorIds, $purposeQuery
        );
        $pizzerias = Pizzeria::query()->select('id', 'name')->get();

        return response()->json([
           'categories' => $categories,
           'pizzerias' => $pizzerias,
        ]);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $q = $request->get('q');

        $categories = Category::query();
        if($q) $categories->where('name', 'like', "%$q%");

        return MinifiedCategoryResource::collection($categories->get());
    }
}

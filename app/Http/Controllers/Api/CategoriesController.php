<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\MinifiedCategoryResource;
use App\Models\Category;
use App\Models\Contractor;
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
        $pizzeriaIds = request()->get('pizzeriaIds');
        $contractorIds = request()->get('contractorIds');
        $purposeQuery = request()->get('purposeQuery');

        $categories = CategoryService::getCategoryTree(
            true, $startDate, $endDate, $groupBy, $pizzeriaIds, $contractorIds, $purposeQuery
        );
        $pizzerias = Pizzeria::query()->select('id', 'name')->get();

        return response()->json([
            'categories' => $categories,
            'pizzerias' => $pizzerias,
        ]);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $paginate = $request->get('paginate', 20);

        $categories = Category::query()->where('id', '!=', 0);
        if ($load = $request->get('load'))
            $categories->with(explode(',', $load));

        if ($q = $request->get('q'))
            $categories->where('name', 'like', "%$q%");

        if($request->has('full_list')) {
            $items = $categories->get();
            return MinifiedCategoryResource::collection($items);
        }

        return MinifiedCategoryResource::collection($categories->paginate($paginate));
    }

    public function show(int $id): MinifiedCategoryResource
    {
        $category = Category::query();
        if ($load = request()->get('load'))
            $category->with(explode(',', $load));

        return MinifiedCategoryResource::make($category->findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|integer',
        ]);
        $category = Category::query()->create($data);
        return response()->json([
            'success' => 1,
            'id' => $category->id,
        ]);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $category = Category::query()->findOrFail($id);
        $category->update($request->only('name', 'parent_id'));
        return response()->json([
            'success' => 1,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::query()->findOrFail($id);
        $category->delete();
        return response()->json([
            'success' => 1,
        ]);
    }
}

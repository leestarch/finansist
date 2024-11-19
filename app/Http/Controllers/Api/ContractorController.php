<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractorResource;
use App\Models\Contractor;
use App\Models\Operation;
use App\Models\OperationRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContractorController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $paginate = $request->get('paginate', 20);
        $contractors = Contractor::query()->filter($request->all());

        return ContractorResource::collection($contractors->paginate($paginate));
    }

    public function show(int $id): ContractorResource
    {
        $contractor = Contractor::query()->findOrFail($id);
        return ContractorResource::make($contractor);
    }

    public function operationCheck(int $contractorId): JsonResponse
    {
        $operations = Operation::query()->where('payee_contractor_id', $contractorId)->get();
        $updatedOperations = OperationRule::validateOperations($operations);

        return response()->json([
            'success' => true,
            'message' => 'Операции проверены, изменено категорий у операций: ' . $updatedOperations,
        ]);
    }
}

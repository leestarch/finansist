<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractorResource;
use App\Models\Contractor;

class ContractorController extends Controller
{
    public function index()
    {
        $contractorQuery = request()->get('contractorQuery', null);

        $contractorQuery === null
            ? $contractors = []
            : $contractors = Contractor::query()
                ->select('id', 'full_name', 'inn_kpp')
                ->where(function ($query) use ($contractorQuery) {
                    $query->where('full_name', 'like', "%$contractorQuery%")
                        ->orWhere('inn_kpp', 'like', "%$contractorQuery%");
                })
                ->paginate(5);
        return ContractorResource::collection($contractors);
    }
}

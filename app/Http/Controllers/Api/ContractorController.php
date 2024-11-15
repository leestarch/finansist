<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractorResource;
use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContractorController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $contractorQuery = $request->get('q', null);
        $paginate = $request->get('paginate', 30);

        $contractors = Contractor::query();

        if($contractorQuery)
            $contractors->where(function ($query) use ($contractorQuery) {
                $query->where('full_name', 'like', "%$contractorQuery%")
                    ->orWhere('inn_kpp', 'like', "%$contractorQuery%");
            });

        return ContractorResource::collection($contractors->paginate($paginate));
    }
}

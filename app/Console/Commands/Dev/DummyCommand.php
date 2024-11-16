<?php

namespace App\Console\Commands\Dev;

use App\Models\Category;
use App\Models\Contractor;
use App\Models\Operation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyCommand extends Command
{
    protected $signature = 'dummy';

    public function handle()
    {
//        $opeartions = Operation::query()->whereHas('categories', function ($query) {
//            $query->where('name', 'like', 'Расчетный Food Cost');
//        })
//            ->whereHas('payeeContractor', function ($query) {
//                $query->where('full_name', 'like', '%ЛОДЖИСТИКС%');
//            })
//            ->whereBetween('date_at', [now()->startOfMonth(), now()->endOfMonth()])
//            ->take(10)->get()->toArray();
//        dd($opeartions);

        $opeartions = Operation::query()->pluck('payer_contractor_id')->toArray();
        $contractors = Contractor::query()
            ->whereNotIn('id', $opeartions)->get()->unique('id')->pluck('full_name');
        dd($contractors);
    }
}

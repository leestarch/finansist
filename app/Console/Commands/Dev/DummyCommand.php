<?php

namespace App\Console\Commands\Dev;

use App\Models\Category;
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
        $opeartions = Operation::query()->whereHas('categories', function ($query) {
            $query->where('name', 'like', 'Расчетный Food Cost');
        })
            ->where('date_at', '>=', Carbon::now()->subDays(15))
            ->get()->toArray();
        dd($opeartions);
    }
}

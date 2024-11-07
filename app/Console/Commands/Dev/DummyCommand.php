<?php

namespace App\Console\Commands\Dev;

use App\Models\Category;
use App\Models\Operation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DummyCommand extends Command
{
    protected $signature = 'dummy';
    public function handle()
    {
        $cats = $categories = Category::query()->where('name', 'Внеоборотные активы')->first();

        dd($cats->operations()
//            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('date', now()->startOfMonth())
            ->pluck('amount')->sum());
    }
}

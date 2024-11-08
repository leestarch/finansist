<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;

class AppRefreshCommand extends Command
{
    protected $signature = 'app.refresh';
    public function handle()
    {
        if(app()->environment() === 'local') {
            $this->call('migrate:refresh');
//            $this->call('load.budget.categories');
            $this->call('db:seed');
//            TODO добавить недостающие категории
        }
    }
}


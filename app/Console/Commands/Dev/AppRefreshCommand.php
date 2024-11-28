<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;

class AppRefreshCommand extends Command
{
    protected $signature = 'app.refresh';
    public function handle()
    {
//        if(app()->environment() === 'local') {
            $this->call('migrate:refresh');
            $this->call('db:seed');
            $this->call('load.budget.categories');
            $this->call('operation.rules.create.command');
            $this->call('operation.categories.table', [
                '--validate' => true
            ]);
//        }
    }
}


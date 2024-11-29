<?php

namespace App\Console\Commands\Dev;

use App\Models\Operation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SeedOperationCommand extends Command
{
    protected $signature = 'seed.operation';
    public function handle()
    {
        if (app()->environment() === 'production') {
            Operation::query()->truncate();
            return;
        }

        $operations = Operation::query();
        $operations->chunk(1000, function ($chunk) {
            $data = $chunk->toArray();
            retry(5, function () use ($data) {
                $response = Http::withoutVerifying()->accept('application/json')
                    ->post('https://fin.lookin.team/api/seed', [
                        'data' => $data
                    ]);

                if ($response->failed()) {
                    dump($response->json());
                }
            });
        });
    }
}

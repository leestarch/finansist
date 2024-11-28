<?php

namespace App\Console\Commands\Dev;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetUserCommand extends Command
{
    protected $signature = 'set.user {--email=} {--password=} {--name=}';
    public function handle(): void
    {
        $email = $this->option('email') ?? 'admin@mail.ru';
        $password = $this->option('password') ?? 'password';
        $name = $this->option('name') ?? 'Admin';
        User::query()->firstOrCreate([
            'email' => $email
        ], [
            'name' => $name,
            'password' => Hash::make($password)
        ]);

        $this->info('User created: ' . $email);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeOwnerCommand extends Command
{
    protected $signature = 'personna:owner {email} {password} {--name=Owner}';

    protected $description = 'Create or update the single shop owner (admin) account.';

    public function handle(): int
    {
        $user = User::updateOrCreate(
            ['email' => $this->argument('email')],
            [
                'name' => $this->option('name'),
                'password' => Hash::make($this->argument('password')),
            ],
        );

        $this->info("Owner ready: {$user->email}");

        return self::SUCCESS;
    }
}

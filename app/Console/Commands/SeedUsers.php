<?php

namespace App\Console\Commands;

use App\Models\Pair;
use App\Surveys\Trait\UsesFinalWeek;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedUsers extends Command
{
    use UsesFinalWeek;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed users for manual testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('migrate:fresh --seed');
        $this->table(['Week', 'Role', 'Email'], $this->generateUsers());
    }

    private function generateUsers()
    {
        $weeks = collect([0, 1, 2, static::finalWeek() - 1, static::finalWeek()]);
        return $weeks
            ->map(fn ($week) => Pair::factory()->createForWeek($week)->users)
            ->flatten()
            ->map(fn ($user) => $user->only('week','role','email'))
            ->toArray();
    }
}

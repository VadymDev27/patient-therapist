<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Pair;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(WeeklySettingsSeeder::class);
    }
}

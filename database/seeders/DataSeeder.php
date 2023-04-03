<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Pair;
use App\Models\Survey;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Surveys\Patient\WeeklySurvey;
use App\Surveys\Trait\UsesFinalWeek;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DataSeeder extends Seeder
{
    use WithFaker, UsesFinalWeek;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $milestones = ['initial','6-month','final'];

        for ($i = 0; $i < 20; $i++) {
        Pair::factory()
            ->randomized(false)
            ->has(User::factory()
                ->withWeeklySurveys(20)
                ->withMilestoneSurveys($milestones)
            )
            ->has(User::factory()
                ->patient()
                ->withWeeklySurveys(20)
                ->withMilestoneSurveys($milestones)
            )
            ->create();
        }
    }
}

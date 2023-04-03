<?php

namespace Database\Seeders;

use App\Models\WeeklySettings;
use Illuminate\Database\Seeder;

class WeeklySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeeklySettings::insert(array_map(fn($val) => ['number' => $val, 'prep' => true, 'video_id' => '520102869', 'video_title' => "Prep video {$val}: Example title here"], range(1,config('surveys.videos.prep.numVideos'))));
        WeeklySettings::insert(array_map(fn($val) => ['number' => $val, 'prep' => false, 'video_id' => '520102869', 'video_title' => "Video {$val}: Example title here and some extra words to make it longer", 'exercises_title' => 'This is an example exercise title'], range(1,config('surveys.videos.weekly.numVideos'))));
    }
}

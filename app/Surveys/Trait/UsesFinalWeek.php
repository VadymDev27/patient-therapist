<?php

namespace App\Surveys\Trait;

trait UsesFinalWeek
{
    private static ?int $finalWeek = null;

    private static function setFinalWeek()
    {
        static::$finalWeek = config('surveys.videos.weekly.numVideos') + 1;
        return static::$finalWeek;
    }

    private static function finalWeek()
    {
        return static::$finalWeek ?? static::setFinalWeek();
    }
}

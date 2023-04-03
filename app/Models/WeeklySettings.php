<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WeeklySettings extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'prep' => 'boolean'
    ];

    public function activitiesFile()
    {
        return $this->prep ? null : Storage::url('exercises/' . static::activitiesFilename($this->number));
    }

    public static function transcriptFilename(int $number, bool $prep=false): string
    {
        return $prep
            ? "TOPDD_RCT_PreparatoryVideo{$number}_Transcript.pdf"
            : "TOPDD_RCT_Week{$number}Video_Transcript.pdf";
    }

    public static function activitiesFilename(int $number): string
    {
        return "TOPDD_RCT_Week{$number}_Activities.pdf";
    }

    /**
     * @param int $number
     * @param bool $prep=false
     *
     * @return WeeklySettings
     */
    public static function findByNumber(int $number, bool $prep=false): WeeklySettings | null
    {
        return self::where('number', $number)->where('prep', $prep)->first();
    }
}

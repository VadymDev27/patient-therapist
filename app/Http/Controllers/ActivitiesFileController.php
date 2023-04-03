<?php

namespace App\Http\Controllers;

use App\Models\WeeklySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivitiesFileController extends Controller
{
    public function download(Request $request, int $number)
    {
        abort_if($request->user()->week < $number, 403);
        return Storage::download('public/exercises/' . WeeklySettings::activitiesFilename($number));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\WeeklySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TranscriptController extends Controller
{
    public function show(Request $request, int $number, ?string $prep=null)
    {
        return Storage::download('transcripts/' . WeeklySettings::transcriptFilename($number,! is_null($prep)));
    }
}

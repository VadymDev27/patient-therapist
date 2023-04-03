<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $record = Analytics::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'number' => $data['number'],
                'prep' => $data['prep'],
                'rewatch' => $data['rewatch']
            ],
            [ 'cumulative_seconds_watched' => 0 ]
        );
        $record->increment('cumulative_seconds_watched', intdiv( $data['elapsed_time'], 1000  ));
    }

    public function index()
    {
        Log::info('Get request to analytics');

        return response()->json(Analytics::all()->toArray());
    }
}

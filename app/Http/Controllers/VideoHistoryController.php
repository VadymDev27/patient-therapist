<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WeeklySettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VideoHistoryController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(WeeklySettings::class, 'video');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return view('view-history', [
            'prep' => WeeklySettings::where('prep',true)
                ->when(($user->week === 0), function (Builder $query) use ($user) {
                    return $query->where('number', '<', $user->getPrepVideoNumber());
                })
                ->get(),
            'weekly' => WeeklySettings::where('prep',false)->where('number','<',$user->week)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeeklySettings  $weeklySettings
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, WeeklySettings $video)
    {
        return view('rewatch-video', [
            'video' => $video
        ]);
    }

}

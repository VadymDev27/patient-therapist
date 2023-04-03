<?php

namespace App\Http\Controllers;

use App\Models\WeeklySettings;
use Illuminate\Http\Request;

class WeeklySettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.weekly-settings', [
            'prep' => WeeklySettings::where('prep',true)->get(),
            'weekly' => WeeklySettings::where('prep',false)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeeklySettings  $weeklySettings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WeeklySettings $weeklySettings)
    {
        $weeklySettings->update($request->only(['video_title','video_id','exercises_title']));
        $status = $weeklySettings->prep ? 'prep-settings-saved' : 'weekly-settings-saved';

        if ($request->hasFile('exercises')) {
            $request->exercises->storePubliclyAs('public/exercises', WeeklySettings::activitiesFilename($weeklySettings->number));
        }

        return back()->with($status, $weeklySettings->number);
    }

}

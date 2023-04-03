<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use App\Models\Survey;
use App\Models\WeeklySettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DataController extends Controller
{
    private ZipArchive $zip;

    public function generate()
    {
        $categories = [
            [
                'name' => 'weekly',
                'hide' => ['type']
            ],
            [
                'name' => 'milestone',
                'hide' => ['week']
            ],
            [
                'name' => 'discontinuation',
                'hide' => ['type','week']
            ],
            [
                'name' => 'screening',
                'hide' => ['type','week']
            ],
        ];

        $roles = [
            [
                'name' => 'patients',
                'is_therapist' => false,
            ],
            [
                'name' => 'therapists',
                'is_therapist' => true
            ]
        ];

        $this->zip = new ZipArchive();
        $this->zip->open(Storage::path('data.zip'), ZipArchive::OVERWRITE);

        foreach ($roles as $r) {
            $q = Survey::with('user')->whereRelation('user','is_therapist',$r['is_therapist']);

            foreach ($categories as $c) {
                $data = Survey::with('user')->whereRelation('user','is_therapist',$r['is_therapist'])
                    ->where('category', $c['name'])
                    ->get()
                    ->makeHidden(array_merge(['user','data','category'], $c['hide']))
                    ->map(fn (Survey $survey) =>
                        array_merge(
                            ['pair_id' => $survey->user->pair_id],
                            $survey->toArray(),
                            Arr::except($survey->data, ['_progress'])
                        )
                    )
                    ->toArray();

                $this->addToZip($data, "{$r['name']}_{$c['name']}.csv");
            }
        }

        $this->addToZip(Analytics::all()->toArray(), 'analytics.csv');

        $this->zip->close();

        return response('Data refreshed successfully.');
    }

    private function addToZip(array $data, string $filename)
    {
        $temp = fopen('php://temp','w');

        if (count($data) > 0) {
            fputcsv($temp, array_keys($data[0]));
            foreach ($data as $line) {
                fputcsv($temp, $line);
            }
        }

        rewind($temp);
        $this->zip->addFromString($filename, stream_get_contents($temp));
        fclose($temp);
    }

    public function create(Request $request)
    {
        $time = new Carbon(Storage::lastModified('data.zip'));
        return view('admin.data-download', ['time' => $time->diffForHumans()])
            ->with('status', $request->get('refresh') ? 'data-refreshing' : '');
    }

    public function download()
    {
        return Storage::download('data.zip');
    }
}

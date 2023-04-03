<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Pair;
use App\Models\User;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestUserController extends Controller
{
    public function index()
    {
        return view('admin.test-users',[
            'users' => User::where('is_test',true)->get()
        ]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.make-test-user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('waitlist')) {
            Pair::factory()->createTestforWeek(
                $request->input('week',0),
                (bool) $request->input('waitlist')
            );
        }

        if ($request->input('therapist_screening') === 'no') {
            User::factory()->test()->create();
        }

        if ($request->input('patient_screening') === 'no') {
            Pair::createFromUsers(
                User::factory()->test()->eligible()->create(),
                User::factory()->test()->patient()->create()
            );
        }

        if ($request->input('randomized') === 'no') {
            Pair::createFromUsers(
                User::factory()->test()->eligible()->create(),
                User::factory()->test()->patient()->eligible()->create()
            );
        }

        return redirect()->route('test-users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.update-test-user', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.update-test-user', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->action === 'toggle_time_travel') {
            $user->test_time_travel = ! $user->test_time_travel;
            $user->save();
        }

        if ($request->action === 'toggle_can_go_ahead') {
            $user->test_can_go_ahead = ! $user->test_can_go_ahead;
            $user->save();
        }

        if ($request->hasAny(['years','months','weeks'])) {
            $user->timeTravelAllRelations($this->getInterval($request));
        }
        return redirect()->back();
    }

    private function getInterval(Request $request)
    {
        $options = collect(['years','months','weeks'])
            ->flip()
            ->map(fn ($item, $key) => $request->get($key,0))
            ->values()
            ->toArray();

        return CarbonInterval::create(...$options);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        (!! $user->pair) ? $user->pair->delete() : $user->delete();
        return redirect()->route('test-users.index')->with('status','Test user and coparticipant successfully deleted.');
    }

    public function login(Request $request, User $user)
    {
        $request->session()->put('remember_web', $request->user()->id);
        Auth::loginUsingId($user->id);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $admin = Admin::find(session('remember_web'));
        Auth::login($admin);

        return redirect()->route('test-users.index');
    }
}

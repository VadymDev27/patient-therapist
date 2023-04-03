<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Pair;
// @todo refactor registration

class RegisteredPatientController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, User $therapist)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (!! $therapist->pair) {
            abort(403);
            // @todo: customize the error message page
        }
        return view('auth.register')
                    ->with('therapistId',$therapist->id);
    }


    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     */
    public function store(Request $request, User $therapist)
    {
        if (!! $therapist->pair) {
            abort(403);
        }
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_therapist' => false,
            'is_test' => $therapist->is_test
        ]);

        Pair::createFromUsers($user,$therapist);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

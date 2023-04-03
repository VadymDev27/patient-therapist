<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;
use App\Notifications\PatientInvitation;

use Illuminate\Http\Request;

class PatientInvitationNotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        abort_unless( ((is_null($user->pair))
            && ($user->is_therapist)
            && ($user->is_eligible)), 403 );

        $user->notify(new PatientInvitation());
        return back()->with('status', 'invitation-link-sent');
    }
}

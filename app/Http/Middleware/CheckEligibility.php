<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class CheckEligibility
{
    /**
     * Check to make sure user and coparticipant are both eligible.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! ($request->user()->is_eligible && $request->user()->getCoParticipant()->is_eligible)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}

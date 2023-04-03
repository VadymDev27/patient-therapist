<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class CanAccessStudyContent
{
    /**
     * Check to make sure user has appropriate role (therapist vs. patient).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->canAccessStudyContent()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

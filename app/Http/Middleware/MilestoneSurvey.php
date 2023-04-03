<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class MilestoneSurvey
{
    /**
     * Check to make sure user has appropriate role (therapist vs. patient).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $slug=null)
    {
        $slug = $slug ?? $request->route()->parameter('slug');
        if ($request->user()->milestoneSurveyDue() !== $slug) {
            return redirect(RouteServiceProvider::HOME);
        }
        return $next($request);
    }
}

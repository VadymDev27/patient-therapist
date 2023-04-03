<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class MatchWeek
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $week)
    {
        if ($request->user()->week == 31) {
            return $next($request);
        }
        if ($request->user()->week != $week) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

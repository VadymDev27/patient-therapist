<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class CheckGroup
{
    /**
     * Check to make sure user has appropriate role (therapist vs. patient).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $group)
    {
        if ($request->user()->group !== $group) {
            return abort(403);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class AdminCan
{
    /**
     * Check to make sure user can perform that action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $action
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $action)
    {
        if (! $request->user()->canDo($action)) {
            return abort(403);
        }

        return $next($request);
    }
}

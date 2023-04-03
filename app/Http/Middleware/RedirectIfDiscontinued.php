<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class RedirectIfDiscontinued
{
    /**
     * Redirect user to dashboard if discontinued
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->discontinued()) {
            return redirect(RouteServiceProvider::HOME);
        }
        return $next($request);
    }
}

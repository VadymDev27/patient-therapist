<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class CheckRole
{
    /**
     * Check to make sure user has appropriate role (therapist vs. patient).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (($role === 'any' && ! $request->user->is_admin) || $request->user()->role !== $role) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}

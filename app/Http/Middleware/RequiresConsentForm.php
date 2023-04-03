<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequiresConsentForm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $suffix = null)
    {
        $user = $request->user();
        $surveyType = $request->segment(3);
        $consentType = $suffix ? "consent-{$suffix}" : 'consent';
        if (! $user->hasCompletedSurvey($surveyType . '-' . $consentType)) {
            return redirect()->route("survey.{$user->role}.{$consentType}.create", ['slug' => $surveyType]);
        }
        return $next($request);
    }
}

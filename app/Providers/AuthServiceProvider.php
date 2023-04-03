<?php

namespace App\Providers;

use App\Models\WeeklySettings;
use App\Policies\VideoHistoryPolicy;
use App\Policies\WeeklySettingsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        WeeklySettings::class => VideoHistoryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('custom-eloquent', function ($app,  array $config) {
            return new UserProvider($app['hash'], $config['model']);
        });
    }
}

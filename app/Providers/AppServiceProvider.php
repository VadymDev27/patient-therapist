<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::if('group', function ($group) {
            return Auth::check() && Auth::user()->group === $group;
        });

        Blade::if('cando', function ($action) {
            return Auth::user()->canDo($action);
        });

        Blade::if('candoany', function (array $actions) {
            $permissions = Auth::user()->admin_permissions;
            return ! is_null(Arr::first(
                $actions,
                function ($value, $key) use ($permissions) {
                    return in_array($value,$permissions);
                }
            ));
        });

        Blade::if('test', function () {
            return Auth::check() &&
                (method_exists(Auth::user(), 'isTest')
                    ? Auth::user()->isTest()
                    : false);
        });
    }
}

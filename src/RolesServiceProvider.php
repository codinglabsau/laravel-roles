<?php

namespace Codinglabs\Roles;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/roles.php' => config_path('roles.php'),
        ], 'roles-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'roles-migrations');

        Gate::define('role', function ($user, ...$roles) {
            return $user->hasRole($roles);
        });

        Blade::if('role', function (...$roles) {
            if (auth()->check()) {
                return auth()->user()->hasRole($roles);
            }

            return false;
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/roles.php', 'roles');
    }
}

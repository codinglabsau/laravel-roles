<?php

namespace Codinglabs\Roles;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/roles.php' => config_path('roles.php'),
        ]);

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Gate::define('role', function ($user, ...$roles) {
            return $user->hasRole($roles);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/roles.php', 'roles');
    }
}

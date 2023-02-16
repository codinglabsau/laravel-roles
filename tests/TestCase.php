<?php

namespace Codinglabs\Roles\Tests;

use Codinglabs\Roles\HasRoles;
use Codinglabs\Roles\CheckRole;
use Illuminate\Support\Facades\Route;
use Codinglabs\Roles\RolesServiceProvider;
use Illuminate\Foundation\Auth\User as AuthUser;
use Orchestra\Testbench\TestCase as BaseTestClass;

class TestCase extends BaseTestClass
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('vendor:publish', ['--tag' => 'roles-migrations'])->run();
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->loadLaravelMigrations(['--database' => 'testbench']);

        Route::get('test-middleware', function () {
            return 'ok';
        })->middleware(CheckRole::class . ':admin');

        $this->user = User::create([
            'name' => 'Laravel Roles',
            'email' => 'laravel@roles.test',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => '1234',
        ]);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            RolesServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}

class User extends AuthUser
{
    use HasRoles;

    protected $guarded = [];
}

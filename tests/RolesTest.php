<?php

namespace Orchestra\Testbench\Tests\Databases;

use Codinglabs\Roles\HasRoles;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Codinglabs\Roles\RolesServiceProvider;
use Illuminate\Foundation\Auth\User as AuthUser;

class RolesTest extends TestCase
{
    /** @var User */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('vendor:publish', ['--tag' => 'roles-migrations'])->run();
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->user = User::create([
            'name' => 'Laravel Roles',
            'email' => 'laravel@roles.test',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => '1234',
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            RolesServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /** @test */
    public function returns_false_when_no_user_or_roles_exist()
    {
        $this->assertFalse(Gate::check('role', 'admin'));
        $this->assertFalse(Gate::check('role', ['admin']));
    }

    /** @test */
    public function returns_false_when_user_has_no_matching_roles()
    {
        Auth::login($this->user);
        $this->assertFalse(Gate::check('role', 'admin'));
        $this->assertFalse(Gate::check('role', ['admin']));

        $this->user->roles()->create(['name' => 'employee']);
        $this->user->roles()->attach('employee');
        $this->assertFalse(Gate::check('role', 'admin'));
        $this->assertFalse(Gate::check('role', ['admin']));
    }

    /** @test */
    public function returns_true_when_user_has_matching_role()
    {
        $this->user->roles()->create(['name' => 'admin']);
        $this->user->roles()->attach('admin');
        Auth::login($this->user);

        $this->assertTrue(Gate::check('role', 'admin'));
        $this->assertTrue(Gate::check('role', ['admin']));
        $this->assertTrue(Gate::check('role', ['manager', 'admin']));
    }
}

class User extends AuthUser
{
    use HasRoles;

    protected $guarded = [];
}

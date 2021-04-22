<?php

namespace Codinglabs\Roles\Tests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class GateTest extends TestCase
{
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

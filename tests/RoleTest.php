<?php

namespace Codinglabs\Roles\Tests;

class RoleTest extends TestCase
{
    /** @test */
    public function has_role_false_when_new_user()
    {
        $this->assertFalse((new User())->hasRole('admin'));
    }

    /** @test */
    public function has_role_false_when_role_not_found()
    {
        $this->assertFalse($this->user->hasRole('admin'));
    }

    /** @test */
    public function has_role_true_when_role_exists()
    {
        $this->user->roles()->create(['name' => 'admin']);
        $this->user->roles()->attach('admin');

        $this->assertTrue($this->user->hasRole('admin'));
    }

    /** @test */
    public function can_create_acls()
    {
        $this->user->resource()->create([
            'role_id' => 2,
            'user_id' => $this->user->id,
            'resource_type' => User::class,
            'resource_id' => 1,
        ]);

        $this->assertDatabaseHas('role_acls', [
            'role_id' => 2,
            'user_id' => $this->user->id,
            'resource_type' => User::class,
            'resource_id' => 1,
        ]);
    }
}

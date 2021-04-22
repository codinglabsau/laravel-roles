<?php

namespace Codinglabs\Roles\Tests;

use Codinglabs\Roles\CheckRole;
use Illuminate\Support\Facades\Route;

class MiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('test-middleware', function () {
            return 'ok';
        })->middleware(CheckRole::class . ':admin');
    }

    /** @test */
    public function throws_401_when_unauthenticated()
    {
        $this->get('test-middleware')
            ->assertStatus(401);
    }

    /** @test */
    public function throws_403_when_role_not_found()
    {
        $this->actingAs($this->user)
            ->get('test-middleware')
            ->assertStatus(403);
    }

    /** @test */
    public function passes_middleware_when_role_exists()
    {
        $this->user->roles()->create(['name' => 'admin']);
        $this->user->roles()->attach('admin');

        $this->actingAs($this->user)
            ->get('test-middleware')
            ->assertOk();
    }
}

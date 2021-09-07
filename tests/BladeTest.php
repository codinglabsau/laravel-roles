<?php

namespace Codinglabs\Roles\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class BladeTest extends TestCase
{
    use InteractsWithViews;

    /** @test */
    public function blade_directive_is_evaluated()
    {
        $view = $this->blade(
            "@role('admin') Hello admin @endrole"
        );

        $view->assertDontSee('@role');
    }

    /** @test */
    public function hides_partial_view_when_user_does_not_have_role()
    {
        $view = $this->blade(
            "@role('admin') Hello admin @endrole"
        );

        $view->assertDontSee('Hello admin');

        $this->actingAs($this->user);
        $view->assertDontSee('Hello admin');
    }

    /** @test */
    public function shows_partial_view_when_user_has_role()
    {
        $this->user->roles()->create(['name' => 'admin']);
        $this->user->roles()->attach('admin');

        $this->actingAs($this->user);

        $view = $this->blade(
            "@role('admin') Hello admin @endrole"
        );

        $view->assertSee('Hello admin');
    }
}

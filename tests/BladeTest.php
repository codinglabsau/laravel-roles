<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

uses(InteractsWithViews::class);

it('evaluates the blade directive', function () {
    $view = $this->blade(
        "@role('admin') Hello admin @endrole"
    );

    $view->assertDontSee('@role');
});

it('hides partial view when user does not have role', function () {
    $view = $this->blade(
        "@role('admin') Hello admin @endrole"
    );

    $view->assertDontSee('Hello admin');

    $this->actingAs($this->user);
    $view->assertDontSee('Hello admin');
});

it('shows partial view when user has role', function () {
    $this->user->roles()->create(['name' => 'admin']);
    $this->user->roles()->attach('admin');

    $this->actingAs($this->user);

    $view = $this->blade(
        "@role('admin') Hello admin @endrole"
    );

    $view->assertSee('Hello admin');
});

<?php

use Codinglabs\Roles\CheckRole;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('test-middleware', function () {
        return 'ok';
    })->middleware(CheckRole::class . ':admin');
});

it('throws 401 when unauthenticated', function () {
    $this->get('test-middleware')
        ->assertStatus(401);
});

it('throws 403 when role not found', function () {
    $this->actingAs($this->user)
        ->get('test-middleware')
        ->assertStatus(403);
});

it('passes middleware when role exists', function () {
    $this->user->roles()->create(['name' => 'admin']);
    $this->user->roles()->attach('admin');

    $this->actingAs($this->user)
        ->get('test-middleware')
        ->assertOk();
});

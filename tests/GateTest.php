<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

it('returns false when no user or roles exist', function () {
    expect(Gate::check('role', 'admin'))->toBeFalse();
    expect(Gate::check('role', ['admin']))->toBeFalse();
});

it('returns false when user has no matching roles', function () {
    Auth::login($this->user);
    expect(Gate::check('role', 'admin'))->toBeFalse();
    expect(Gate::check('role', ['admin']))->toBeFalse();

    $this->user->roles()->create(['name' => 'employee']);
    $this->user->roles()->attach('employee');
    expect(Gate::check('role', 'admin'))->toBeFalse();
    expect(Gate::check('role', ['admin']))->toBeFalse();
});

it('returns true when user has matching role', function () {
    $this->user->roles()->create(['name' => 'admin']);
    $this->user->roles()->attach('admin');
    Auth::login($this->user);

    expect(Gate::check('role', 'admin'))->toBeTrue();
    expect(Gate::check('role', ['admin']))->toBeTrue();
    expect(Gate::check('role', ['manager', 'admin']))->toBeTrue();
});

# Laravel Roles

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codinglabsau/laravel-roles.svg?style=flat-square)](https://packagist.org/packages/codinglabsau/laravel-roles)
[![Test](https://github.com/codinglabsau/laravel-roles/actions/workflows/run-tests.yml/badge.svg)](https://github.com/codinglabsau/laravel-roles/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/codinglabsau/laravel-roles.svg?style=flat-square)](https://packagist.org/packages/codinglabsau/laravel-roles)

A super simple roles system for Laravel. 

## Installation
Via Composer

``` bash
$ composer require codinglabsau/laravel-roles
```

## Usage

### Publish Assets
```
php artisan vendor:publish --provider="Codinglabs\Roles\RolesServiceProvider"
```
##### Or Publish Specific Assets
```
php artisan vendor:publish --tag="roles-config"
php artisan vendor:publish --tag="roles-migrations"
```
### Migrations
You will need to ensure that you have published the migrations and run `php artisan migrate`.
### Add the trait
Add the `HasRoles` trait to your user model:

```php
use Codinglabs\Roles\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;
}
```

### Create roles
```php
$role = \Codinglabs\Roles\Role::create(['name' => 'manager']);
```

### Get roles
```php
$managerRole = \Codinglabs\Roles\Role::whereName('manager')->first();
```

### Associate roles
Under the hood we are using Eloquent many-to-many relationships.
```php
use Codinglabs\Roles\Role;

// attach multiple roles
$user->roles()->attach([
    Role::whereName('employee')->first()->id,
    Role::whereName('manager')->first()->id,
]);

// detach a single role
$user->roles()->detach(Role::whereName('employee')->first());

// update roles to match array
$user->roles()->sync([
    Role::whereName('employee')->first()->id,
]);

// ensure roles in array are attached without detaching others
$user->roles()->syncWithoutDetaching([
    Role::whereName('employee')->first()->id,
]);
```

### Protect routes with middleware
In `App\Http\Kernel`, register the middeware: 
```php
protected $routeMiddleware = [
    // ...
    'role' => \Codinglabs\Roles\CheckRole::class,
];
```
And then call the middleware in your routes, seperating multiple roles with a pipe:
```php
Route::middleware('role:employee')->...
Route::middleware('role:manager|admin')->...
```

Or with a gate:
```php
class UserController extends Controller
{
    public function destroy()
    {
        $this->authorize('role', 'admin');
    }
}
```

Or in the construct method in a controller:
```php
class ManagerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:manager');
    }
}
```

If the middleware check fails, a 403 response will be returned.

### Check roles on a user
Call hasRole on the user model:
```php
// check a single role
$user->hasRole('foo');

// check whether any role exists
$user->hasRole(['bar', 'baz']);

// get all roles
$user->roles;
```

### Sharing roles with UI (Inertiajs example)
```php
// AppServiceProvider.php
Inertia::share([
    'auth' => function () {
        return [
            'user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'roles' => Auth::user()->roles->pluck('name'),
            ] : null
        ];
    }
]);
```
```javascript
// app.js
Vue.mixin({
  methods: {
    hasRole: function(role) {
      return this.$page.auth.user.roles.includes(role)
    }
  }
})
```
```html
<!-- SomeComponent.vue -->
<div v-if="hasRole('manager')">I am a manager</div>
```
## Configuration

```
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | You may replace the models here with your own if you need to use a custom
    | model.
    |
    */

    'models' => [
        'role' => \Codinglabs\Roles\Role::class
    ]
];

```
## Upgrading from v1 to v2
Please see [upgrading from v1 to v2](UPGRADING.md) for details and instructions to avoid any issues after upgrading to v2.

## Contributing
Please see [contributing.md](contributing.md) for details and a todolist.

## Security
If you discover any security related issues, create an issue on GitHub.

## Credits
- [Steve Thomas](https://github.com/stevethomas)
- [All Contributors](../../contributors)

## License
MIT. Please see the [license file](LICENSE.md) for more information.

## About Coding Labs
Coding Labs is a web app development agency based on the Gold Coast, Australia. See our open source projects [on our website](https://codinglabs.com.au/open-source).

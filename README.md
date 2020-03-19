# Roles

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

A super simple roles system for Laravel. 

## Installation

Via Composer

``` bash
$ composer require codinglabsau/laravel-roles
```

## Usage
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

### Associate roles
Under the hood we are using Eloquent many-to-many relationships.
```php
$user->roles()->attach([
    \Codinglabs\Roles\Role::whereName('employee')->first()->id,
    \Codinglabs\Roles\Role::whereName('manager')->first()->id,
]);

$user->roles()->detach(\Codinglabs\Roles\Role::whereName('employee')->first()->id);

$user->roles()->sync([
    \Codinglabs\Roles\Role::whereName('employee')->first()->id,
]);

$user->roles()->syncWithoutDetaching([
    \Codinglabs\Roles\Role::whereName('employee')->first()->id,
]);
```

### Protect routes with middleware
In `App\Http\Kernel`, register the middeware: 
```php
protected $routeMiddleware = [
        ...
        'role' => \Codinglabs\Roles\CheckRole::class,
    ];
```
And then call the middleware in your routes, seperating OR conditions with a pipe:
```php
Route::middleware('role:employee')->...
Route::middleware('role:manager|admin')->...
```

### Everywhere else
Call hasRole on the user model:
```php
// check a single role
$user->hasRole('foo');

// check whether any role exists
$user->hasRole(['bar', 'baz']);

// get all roles
$user->roles;
```

## Change log
Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing
``` bash
$ composer test
```

## Contributing
Please see [contributing.md](contributing.md) for details and a todolist.

## Security
If you discover any security related issues, create an Issue.

## Credits
- [Steve Thomas][https://github.com/stevethomas]
- [All Contributors](../../contributors)

## License
MIT. Please see the [license file](LICENSE.md) for more information.

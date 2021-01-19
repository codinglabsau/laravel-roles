<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Migrations
    |--------------------------------------------------------------------------
    |
    | This will determine whether to use default migrations or not. This should
    | be disabled if you have published the migration files.
    |
    */

    'default_migrations' => true,

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

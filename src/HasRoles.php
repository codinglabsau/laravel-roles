<?php

namespace Codinglabs\Roles;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(config('roles.models.role'))
            ->withTimestamps();
    }

    public function resourceables(): MorphMany
    {
        return $this->morphMany(config('roles.models.role_acls'), 'resourceable');
    }

    public function hasRole(string|array|Role $role): bool
    {
        $this->loadMissing('roles');

        if (! is_array($role)) {
            $role = (array)$role;
        }

        $roleNames = collect($role)
            ->map(fn ($roleData) => $roleData instanceof Role ? $roleData->name : $roleData)
            ->toArray();

        return $this->roles
            ->whereIn('name', $roleNames)
            ->isNotEmpty();
    }
}

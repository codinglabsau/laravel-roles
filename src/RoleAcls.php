<?php

namespace Codinglabs\Roles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RoleAcls extends Model
{
    protected $guarded = [];

    public function resourceable(): MorphTo
    {
        return $this->morphTo();
    }
}

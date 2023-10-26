<?php

namespace App\Models\Filters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    public function name(string $name): UserFilter
    {
        return $this->where("name", "ilike", "%{$name}%");
    }
}

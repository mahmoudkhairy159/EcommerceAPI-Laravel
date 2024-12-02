<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Laratrust\Models\Role as RoleModel;

class Role extends RoleModel
{
    use Filterable;
    public $guarded = [];
}

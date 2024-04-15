<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpartieRole;

class Role extends SpartieRole
{
    use HasFactory;
    protected $table = 'roles';

    public static function getPermissionsCount($role)
    {
        return self::join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')->where('roles.id', '=', $role)->count();
    }
}

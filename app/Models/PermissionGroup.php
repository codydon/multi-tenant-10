<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'description',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'permissionGroupID');
    }

    public static function allPermissionGroups()
    {
        // Get All Groups
        $groups = self::all();
        // Create an Empty Array
        $permissions = [];
        // Get Permissions for each Group
        foreach ($groups as $group) {
            $group_permissions = [
                'group' => self::where('id', '=', $group->id)->first(),
                'permissions' => self::join('permissions', 'permissions.permissionGroupID', 'permission_groups.id')->where('permission_groups.id', '=', $group->id)->get(),
            ];
            array_push($permissions, $group_permissions);
        }
        // Return Permissions
        return $permissions;
    }
    public static function getAllPermissions()
    {
        $groups = self::all();
        $permissions =  self::join('permissions', 'permissions.permissionGroupID', 'permission_groups.id')->get();

        return [
            'groups' => $groups,
            'permissions' => $permissions,
        ];
    }
}
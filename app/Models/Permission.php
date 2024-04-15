<?php

namespace App\Models;

use App\Models\PermissionGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpartiePermission;
// use Stancl\Tenancy\Tenant;
use App\Models\Tenant as TenantsModel;

class Permission extends SpartiePermission
{
    use HasFactory;

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'permissionGroupID');
    }
    public static function loopOverTenantsMigrating($tenant_id = null)
    {
        if ($tenant_id) {
            $tenant = Tenant::find($tenant_id);
            $package_id = TenantDetail::where('tenant_id', $tenant_id)->first()->package_id;
            tenancy()->initialize($tenant);
            return self::migratePermissions($package_id);
        } else {
            $switching = [];
            $tenants = TenantsModel::all();
            foreach ($tenants as $tenant) {
                tenancy()->initialize($tenant);
                $package_id = TenantDetail::where('tenant_id', $tenant->id)->first()->package_id;
                $switching[$tenant->id] = self::migratePermissions($package_id);
            }
            return $switching;
        }
    }

    public static function migratePermissions($package_id)
    {
        $incomingPermissions = self::permissionsToSeed();

        // Handle permission groups
        $existingPermissionGroups = PermissionGroup::all();
        foreach ($existingPermissionGroups as $existingGroup) {
            $found = false;
            foreach ($incomingPermissions as $incomingGroup) {
                if ($existingGroup->group_name === $incomingGroup['group_name']) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                info('Deleting group: ' . $existingGroup->group_name);
                $existingGroup->permissions()->delete();
                $existingGroup->delete();
            }
        }

        foreach ($incomingPermissions as $incomingGroup) {
            $existingGroup = PermissionGroup::where('group_name', 'like', $incomingGroup['group_name'])->first();
            if (!$existingGroup) {
                info('Creating group: ' . $incomingGroup['group_name']);
                $existingGroup = PermissionGroup::create([
                    'group_name' => $incomingGroup['group_name'],
                    'description' => $incomingGroup['description'],
                ]);
            }

            $groupId = $existingGroup->id;

            // Handle permissions
            $existingPermissions = Permission::where('permissionGroupID', $groupId)->get();
            foreach ($existingPermissions as $existingPermission) {
                $found = false;
                foreach ($incomingGroup['permissions'] as $incomingPermission) {
                    if ($existingPermission->name === $incomingPermission['name']) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    info('Deleting permission: ' . $existingPermission->name);
                    $existingPermission->delete();
                }
            }

            foreach ($incomingGroup['permissions'] as $incomingPermission) {
                $existingPermission = Permission::where('permissionGroupID', $groupId)
                    ->where('name', 'like', $incomingPermission['name'])
                    ->first();

                if (!$existingPermission && $package_id == $incomingPermission['package_id']) {
                    info('Creating permission: ' . $incomingPermission['name']);
                    $newPermission = Permission::create([
                        'name' => $incomingPermission['name'],
                        'permissionGroupID' => $groupId,
                        'package_id' => $package_id,
                    ]);
                    $permissionName = ucwords($incomingPermission['permission_name']);
                    $newPermission->update([
                        'permission_name' => $permissionName,
                        'description' => 'Permission to ' . $permissionName,
                    ]);
                } elseif ($existingPermission && !isset($incomingPermission['package_id'])) {
                    info('Deleting permission: ' . $existingPermission->name);
                    $existingPermission->delete();
                } else {
                    info('Permission already exists: ' . $incomingPermission['name']);
                }
            }
        }

        $roles = [
            ['role_name' => 'super admin', 'name' => 'super-admin'],
            ['role_name' => 'shares manager', 'name' => 'shares-manager'],
        ];
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']], // search condition
                [                           // data to update or create
                    'name' => $role['name'],
                    'branch_id' => 2,
                    'role_name' => ucwords($role['role_name']),
                    'description' => ucwords($role['role_name']) . ' role',
                    // 'guard_name' => 'web',
                ]
            );
        }
        // Assign Super Admin All Permissions
        $all_permissions = Permission::all();
        $super_admin = Role::where('name', 'LIKE', 'super_admin')->first();

        foreach ($all_permissions as $permission) {
            $super_admin->givePermissionTo($permission);
        }
    }



    public static function permissionsToSeed()
    {
        return [
            [
                'group_name' => 'Manage Users Accounts edited',
                'description' => 'The permissions below are used to manage user account related tasks',
                'permissions' => [
                    ['permission_name' => 'Add User Accounts', 'package_id' => 1, 'name' => 'add-user-accounts'],
                    ['permission_name' => 'View Users Accounts', 'package_id' => 1, 'name' => 'view-user-accounts'],
                    ['permission_name' => 'Update Users Accounts', 'package_id' => 1, 'name' => 'update-user-accounts'],
                    ['permission_name' => 'Delete Users Accounts', 'package_id' => 1, 'name' => 'delete-user-accounts'],
                    ['permission_name' => 'Block & Unblock Users Accounts', 'package_id' => 1, 'name' => 'block-user-accounts'],
                    ['permission_name' => 'Suspend Users Accounts', 'package_id' => 1, 'name' => 'suspend-user-accounts'],
                ],
            ],
            [
                'group_name' => 'Manage Supplier Loans',
                'description' => 'The permissions below are used to manage supplier loan related related tasks',
                'permissions' => [
                    ['permission_name' => 'Add Loans', 'package_id' => 1, 'name' => 'add-loans'],
                    ['permission_name' => 'View Loans', 'package_id' => 1, 'name' => 'view-loans'],
                    ['permission_name' => 'Update Loans', 'package_id' => 1, 'name' => 'update-loans'],
                    ['permission_name' => 'Delete Loans', 'package_id' => 1, 'name' => 'delete-loans'],
                    ['permission_name' => 'Approve Loans', 'package_id' => 1, 'name' => 'approve-loans'],
                    ['permission_name' => 'Disburse Loans', 'package_id' => 1, 'name' => 'disburse-loans'],
                    ['permission_name' => 'Generate Loans Statements', 'package_id' => 1, 'name' => 'generate-supplier-loan-statements'],
                ],
            ],
            [
                'group_name' => 'Manage Supplier shares',
                'description' => 'The permissions below are used to manage supplier shares related tasks',
                'permissions' => [
                    ['permission_name' => 'Shares Dashboard', 'package_id' => 1, 'name' => 'view-share-dashboard'],
                    ['permission_name' => 'Shares Contributions', 'package_id' => 1, 'name' => 'view-share-contributions'],
                    ['permission_name' => 'Shares Transfers', 'package_id' => 1, 'name' => 'view-share-transfers'],
                    ['permission_name' => 'Inactive Shares Accounts', 'package_id' => 1, 'name' => 'view-inactive-share-accounts'],
                    ['permission_name' => 'Closed Shares Accounts', 'package_id' => 1, 'name' => 'view-closed-share-accounts'],
                    ['permission_name' => 'Approve share reversals', 'package_id' => 2, 'name' => 'approve-share-reversals'],
                    ['permission_name' => 'Approve share transfers', 'package_id' => 1, 'name' => 'approve-share-transfers'],
                    ['permission_name' => 'Approve share conversions', 'package_id' => 2, 'name' => 'approve-share-conversions'],
                    ['permission_name' => 'View share accounts', 'package_id' => 1, 'name' => 'view-share-accounts'],
                    ['permission_name' => 'Add & edit share accounts', 'package_id' => 1, 'name' => 'manage-share-accounts'],
                    ['permission_name' => 'Delete share accounts', 'package_id' => 1, 'name' => 'delete-share-accounts'],
                    ['permission_name' => 'View share Products', 'package_id' => 1, 'name' => 'view-share-products'],
                    ['permission_name' => 'Add & edit share products', 'package_id' => 1, 'name' => 'manage-share-products'],
                    ['permission_name' => 'Delete share products', 'package_id' => 1, 'name' => 'delete-share-products'],
                    ['permission_name' => 'Set shares settings', 'package_id' => 1, 'name' => 'share-settings'],
                    ['permission_name' => 'View shares fees', 'package_id' => 1, 'name' => 'view-shares-fees'],
                    ['permission_name' => 'Add &edit shares fees', 'package_id' => 1, 'name' => 'manage-shares-fees'],
                    ['permission_name' => 'Delete shares fees', 'package_id' => 1, 'name' => 'delete-shares-fees'],
                    ['permission_name' => 'Generate shares Statements', 'package_id' => 1, 'name' => 'generate-supplier-shares-statements'],
                    // ['permission_name' => 'sample added', 'name' => 'this is a samp'],
                ],
            ],
            [
                'group_name' => 'ManageSupplier Farms',
                'description' => 'The permissions below are used to manage supplier farms and related tasks',
                'permissions' => [
                    ['permission_name' => 'add supplier farms', 'package_id' => 1, 'name' => 'add-supplier-farms'],
                    ['permission_name' => 'View supplier farms', 'package_id' => 1, 'name' => 'view-supplier-farms'],
                    ['permission_name' => 'Update supplier farms', 'package_id' => 1, 'name' => 'update-supplier-farms'],
                    ['permission_name' => 'delete supplier farms', 'package_id' => 1, 'name' => 'delete-supplier-farms'],
                    ['permission_name' => 'suspend supplier farms', 'package_id' => 1, 'name' => 'suspend-supplier-farms'],
                ],
            ],
            [
                'group_name' => 'Manage Company Accounts',
                'description' => 'The permissions below are used to manage Company Accounts and related tasks',
                'permissions' => [
                    ['permission_name' => 'add company accounts', 'package_id' => 1, 'name' => 'add-company-accounts'],
                    ['permission_name' => 'View company accounts', 'package_id' => 1, 'name' => 'view-company-accounts'],
                    ['permission_name' => 'Update company accounts', 'package_id' => 1, 'name' => 'update-company-accounts'],
                    ['permission_name' => 'delete company accounts', 'package_id' => 1, 'name' => 'delete-company-accounts'],
                    ['permission_name' => 'suspend company accounts', 'package_id' => 1, 'name' => 'suspend-company-accounts'],
                ],
            ],
            [
                'group_name' => 'Manage Payments',
                'description' => 'The permissions below are used to manage payments and related tasks',
                'permissions' => [
                    ['permission_name' => 'add payments', 'package_id' => 1, 'name' => 'add-payments'],
                    ['permission_name' => 'View payments', 'package_id' => 1, 'name' => 'view-payments'],
                    ['permission_name' => 'Update payments', 'package_id' => 1, 'name' => 'update-payments'],
                    ['permission_name' => 'delete payments', 'package_id' => 1, 'name' => 'delete-payments'],
                ],
            ],



        ];
    }
    public static function getRolePermissions($role)
    {
        return self::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->join('roles', 'roles.id', 'role_has_permissions.role_id')
            ->where('roles.id', $role)
            ->select('permissions.*')
            ->get();
    }
    public static function getRolePermissionsCount($role)
    {
        return count(self::getRolePermissions($role));
    }
}

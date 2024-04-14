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

                if (!$existingPermission) {
                    info('Creating permission: ' . $incomingPermission['name']);
                    $newPermission = Permission::create([
                        'name' => $incomingPermission['name'],
                        'permissionGroupID' => $groupId,
                    ]);
                    $permissionName = ucwords($incomingPermission['permission_name']);
                    $newPermission->update([
                        'permission_name' => $permissionName,
                        'description' => 'Permission to ' . $permissionName,
                    ]);
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

    private static function packages()
    {
        return [
            [
                'id' => 1,
                'name' => 'Basic',
                'description' => 'Basic package',
            ],
            [
                'id' => 2,
                'name' => 'Basic',
                'description' => 'Premium package',
            ],
            [
                'id' => 3,
                'name' => 'Advanced',
                'description' => 'Advanced package',
            ],
        ];
    }

    public static function permissionsToSeed()
    {
        return [
            [
                'group_name' => 'Manage Users Accounts edited',
                'description' => 'The permissions below are used to manage user account related tasks',
                'permissions' => [
                    ['permission_name' => 'Add User Accounts', 'name' => 'add-user-accounts'],
                    ['permission_name' => 'View Users Accounts', 'name' => 'view-user-accounts'],
                    ['permission_name' => 'Update Users Accounts', 'name' => 'update-user-accounts'],
                    ['permission_name' => 'Delete Users Accounts', 'name' => 'delete-user-accounts'],
                    ['permission_name' => 'Block & Unblock Users Accounts', 'name' => 'block-user-accounts'],
                    ['permission_name' => 'Suspend Users Accounts', 'name' => 'suspend-user-accounts'],
                ],
            ],
            [
                'group_name' => 'Manage Supplier Loans',
                'description' => 'The permissions below are used to manage supplier loan related related tasks',
                'permissions' => [
                    ['permission_name' => 'Add Loans', 'name' => 'add-loans'],
                    ['permission_name' => 'View Loans', 'name' => 'view-loans'],
                    ['permission_name' => 'Update Loans', 'name' => 'update-loans'],
                    ['permission_name' => 'Delete Loans', 'name' => 'delete-loans'],
                    ['permission_name' => 'Approve Loans', 'name' => 'approve-loans'],
                    ['permission_name' => 'Disburse Loans', 'name' => 'disburse-loans'],
                    ['permission_name' => 'Generate Loans Statements', 'name' => 'generate-supplier-loan-statements'],
                ],
            ],
            [
                'group_name' => 'Manage Supplier shares',
                'description' => 'The permissions below are used to manage supplier shares related tasks',
                'permissions' => [
                    ['permission_name' => 'Shares Dashboard', 'name' => 'view-share-dashboard'],
                    ['permission_name' => 'Shares Contributions', 'name' => 'view-share-contributions'],
                    ['permission_name' => 'Shares Transfers', 'name' => 'view-share-transfers'],
                    ['permission_name' => 'Inactive Shares Accounts', 'name' => 'view-inactive-share-accounts'],
                    ['permission_name' => 'Closed Shares Accounts', 'name' => 'view-closed-share-accounts'],
                    ['permission_name' => 'Approve share reversals', 'name' => 'approve-share-reversals'],
                    ['permission_name' => 'Approve share transfers', 'name' => 'approve-share-transfers'],
                    ['permission_name' => 'Approve share conversions', 'name' => 'approve-share-conversions'],
                    ['permission_name' => 'View share accounts', 'name' => 'view-share-accounts'],
                    ['permission_name' => 'Add & edit share accounts', 'name' => 'manage-share-accounts'],
                    ['permission_name' => 'Delete share accounts', 'name' => 'delete-share-accounts'],
                    ['permission_name' => 'View share Products', 'name' => 'view-share-products'],
                    ['permission_name' => 'Add & edit share products', 'name' => 'manage-share-products'],
                    ['permission_name' => 'Delete share products', 'name' => 'delete-share-products'],
                    ['permission_name' => 'Set shares settings', 'name' => 'share-settings'],
                    ['permission_name' => 'View shares fees', 'name' => 'view-shares-fees'],
                    ['permission_name' => 'Add &edit shares fees', 'name' => 'manage-shares-fees'],
                    ['permission_name' => 'Delete shares fees', 'name' => 'delete-shares-fees'],
                    ['permission_name' => 'Generate shares Statements', 'name' => 'generate-supplier-shares-statements'],
                    // ['permission_name' => 'sample added', 'name' => 'this is a samp'],
                ],
            ],
            [
                'group_name' => 'ManageSupplier Farms',
                'description' => 'The permissions below are used to manage supplier farms and related tasks',
                'permissions' => [
                    ['permission_name' => 'add supplier farms', 'name' => 'add-supplier-farms'],
                    ['permission_name' => 'View supplier farms', 'name' => 'view-supplier-farms'],
                    ['permission_name' => 'Update supplier farms', 'name' => 'update-supplier-farms'],
                    ['permission_name' => 'delete supplier farms', 'name' => 'delete-supplier-farms'],
                    ['permission_name' => 'suspend supplier farms', 'name' => 'suspend-supplier-farms'],
                ],
            ],
            [
                'group_name' => 'Manage Company Accounts',
                'description' => 'The permissions below are used to manage Company Accounts and related tasks',
                'permissions' => [
                    ['permission_name' => 'add company accounts', 'name' => 'add-company-accounts'],
                    ['permission_name' => 'View company accounts', 'name' => 'view-company-accounts'],
                    ['permission_name' => 'Update company accounts', 'name' => 'update-company-accounts'],
                    ['permission_name' => 'delete company accounts', 'name' => 'delete-company-accounts'],
                    ['permission_name' => 'suspend company accounts', 'name' => 'suspend-company-accounts'],
                ],
            ],
            [
                'group_name' => 'Manage Payments',
                'description' => 'The permissions below are used to manage payments and related tasks',
                'permissions' => [
                    ['permission_name' => 'add payments', 'name' => 'add-payments'],
                    ['permission_name' => 'View payments', 'name' => 'view-payments'],
                    ['permission_name' => 'Update payments', 'name' => 'update-payments'],
                    ['permission_name' => 'delete payments', 'name' => 'delete-payments'],
                ],
            ],

            [
                'group_name' => 'Manage Permissions',
                'description' => 'The permissions below are used to manage system permissions like enabling and disabling permissions and other related tasks',
                'permissions' => [
                    ['permission_name' => 'disable permissions', 'name' => 'disable-permissions'],
                    ['permission_name' => 'enable permissions', 'name' => 'enable-permissions'],
                    ['permission_name' => 'view permissions', 'name' => 'view-permissions'],
                ],
            ],
            [
                'group_name' => 'Manage Bank Details',
                'description' => 'The permissions below are used to manage bank details and other related tasks',
                'permissions' => [
                    ['permission_name' => 'add banks', 'name' => 'add-banks'],
                    ['permission_name' => 'update banks', 'name' => 'update-banks'],
                    ['permission_name' => 'view banks', 'name' => 'view-banks'],
                    ['permission_name' => 'delete banks', 'name' => 'delete-banks'],
                ],
            ],
            [
                'group_name' => 'Manage Supplier Invoices',
                'description' => 'The permissions below are used to manage Supplier Invoices and other related tasks',
                'permissions' => [
                    ['permission_name' => 'add supplier invoices', 'name' => 'add-supplier-invoices'],
                    ['permission_name' => 'update supplier invoices', 'name' => 'update-supplier-invoices'],
                    ['permission_name' => 'view supplier invoices', 'name' => 'view-supplier-invoices'],
                    ['permission_name' => 'delete supplier invoices', 'name' => 'delete-supplier-invoices'],
                ],
            ],
            [
                'group_name' => 'Manage Supplier payments',
                'description' => 'The permissions below are used to manage Supplier payments and other related tasks',
                'permissions' => [
                    ['permission_name' => 'add supplier payments', 'name' => 'add-supplier-payments'],
                    ['permission_name' => 'update supplier payments', 'name' => 'update-supplier-payments'],
                    ['permission_name' => 'view supplier payments', 'name' => 'view-supplier-payments'],
                    ['permission_name' => 'delete supplier payments', 'name' => 'delete-supplier-payments'],
                ],
            ],
            [
                'group_name' => 'Manage Roles',
                'description' => 'The permissions below are used to manage system roles',
                'permissions' => [
                    ['permission_name' => 'add Roles', 'name' => 'add-roles'],
                    ['permission_name' => 'update Roles', 'name' => 'update-roles'],
                    ['permission_name' => 'view Roles', 'name' => 'view-roles'],
                    ['permission_name' => 'delete Roles', 'name' => 'delete-roles'],
                ],
            ],
            [
                'group_name' => 'Manage suppliers',
                'description' => 'The permissions below are used to manage system suppliers',
                'permissions' => [
                    ['permission_name' => 'add suppliers', 'name' => 'add-suppliers'],
                    ['permission_name' => 'update suppliers', 'name' => 'update-suppliers'],
                    ['permission_name' => 'view suppliers', 'name' => 'view-suppliers'],
                    ['permission_name' => 'delete suppliers', 'name' => 'delete-suppliers'],
                    ['permission_name' => 'suspend suppliers', 'name' => 'suspend-suppliers'],
                ],
            ],
            [
                'group_name' => 'Manage Staff Enrolment Age Limits',
                'description' => 'The permissions below are used to manage Staff Enrolment Age Limits',
                'permissions' => [
                    ['permission_name' => 'Update Staff Enrolment Age Limits', 'name' => 'update-staff-age-limits'],
                ],
            ],
            [
                'group_name' => 'Manage Permissions For a Role',
                'description' => 'The permissions below are used to manage system permissions for roles',
                'permissions' => [
                    ['permission_name' => 'add roles permissions', 'name' => 'add-roles-permissions'],
                    ['permission_name' => 'view roles permissions', 'name' => 'view-roles-permissions'],
                    ['permission_name' => 'update roles permissions', 'name' => 'update-roles-permissions'],
                    ['permission_name' => 'delete roles permissions', 'name' => 'delete-roles-permissions'],
                    ['permission_name' => 'assign user roles', 'name' => 'assign-user-roles'],
                    ['permission_name' => 'delete user roles', 'name' => 'delete-user-roles'],
                ],
            ],

            [
                'group_name' => 'Manage System Settings',
                'description' => 'The permissions below are used to manage system settings',
                'permissions' => [
                    ['permission_name' => 'manage social login', 'name' => 'manage-social-login'],
                    ['permission_name' => 'view system settings', 'name' => 'view-system-settings'],
                    ['permission_name' => 'update system settings', 'name' => 'update-system-settings'],
                    ['permission_name' => 'update billing settings', 'name' => 'update-billing-settings'],

                ],
            ],

            [
                'group_name' => 'Manage Dashboards',
                'description' => 'The permissions below are used to manage dashboard',
                'permissions' => [
                    ['permission_name' => 'view admin dashboard', 'name' => 'view-admin-dashboard'],
                ],
            ],

            [
                'group_name' => 'Manage Counties',
                'description' => 'The permissions below are used to manage counties',
                'permissions' => [
                    ['permission_name' => 'add counties', 'name' => 'add-counties'],
                    ['permission_name' => 'view counties', 'name' => 'view-counties'],
                    ['permission_name' => 'update counties', 'name' => 'update-counties'],
                    ['permission_name' => 'delete counties', 'name' => 'delete-counties'],
                ],
            ],

            [
                'group_name' => 'Manage Constituencies',
                'description' => 'The permissions below are used to manage constituencies',
                'permissions' => [
                    ['permission_name' => 'add constituencies', 'name' => 'add-constituencies'],
                    ['permission_name' => 'view constituencies', 'name' => 'view-constituencies'],
                    ['permission_name' => 'update constituencies', 'name' => 'update-constituencies'],
                    ['permission_name' => 'delete constituencies', 'name' => 'delete-constituencies'],
                ],
            ],

            [
                'group_name' => 'Manage Inventories',
                'description' => 'The permissions below are used to manage inventories',
                'permissions' => [
                    ['permission_name' => 'add inventories', 'name' => 'add-inventories'],
                    ['permission_name' => 'view inventories', 'name' => 'view-inventories'],
                    ['permission_name' => 'update inventories', 'name' => 'update-inventories'],
                    ['permission_name' => 'delete inventories', 'name' => 'delete-inventories'],
                ],
            ],

            [
                'group_name' => 'Manage Invoices',
                'description' => 'The permissions below are used to manage invoices',
                'permissions' => [
                    ['permission_name' => 'add invoices', 'name' => 'add-invoices'],
                    ['permission_name' => 'view invoices', 'name' => 'view-invoices'],
                    ['permission_name' => 'update invoices', 'name' => 'update-invoices'],
                    ['permission_name' => 'delete invoices', 'name' => 'delete-invoices'],
                ],
            ],

            [
                'group_name' => 'Manage Incidents',
                'description' => 'The permissions below are used to manage incidents',
                'permissions' => [
                    ['permission_name' => 'add incidents', 'name' => 'add-incidents'],
                    ['permission_name' => 'view incidents', 'name' => 'view-incidents'],
                    ['permission_name' => 'update incidents', 'name' => 'update-incidents'],
                    ['permission_name' => 'delete incidents', 'name' => 'delete-incidents'],
                ],
            ],
            [
                'group_name' => 'Manage Document Types',
                'description' => 'The permissions below are used to manage document types',
                'permissions' => [
                    ['permission_name' => 'add document types', 'name' => 'add-document-types'],
                    ['permission_name' => 'view document types', 'name' => 'view-document-types'],
                    ['permission_name' => 'update document types', 'name' => 'update-document-types'],
                    ['permission_name' => 'delete document types', 'name' => 'delete-document-types'],
                ],
            ],



            [
                'group_name' => 'Manage Deductions',
                'description' => 'The permissions below are used to manage deductions',
                'permissions' => [
                    ['permission_name' => 'add deductions', 'name' => 'add-deductions'],
                    ['permission_name' => 'view deductions', 'name' => 'view-deductions'],
                    ['permission_name' => 'update deductions', 'name' => 'update-deductions'],
                    ['permission_name' => 'delete deductions', 'name' => 'delete-deductions'],
                    ['permission_name' => 'approve deductions', 'name' => 'approve-deductions'],
                ],
            ],

            [
                'group_name' => 'Manage Quotations',
                'description' => 'The permissions below are used to manage quotations',
                'permissions' => [
                    ['permission_name' => 'add quotations', 'name' => 'add-quotations'],
                    ['permission_name' => 'view quotations', 'name' => 'view-quotations'],
                    ['permission_name' => 'update quotations', 'name' => 'update-quotations'],
                    ['permission_name' => 'delete quotations', 'name' => 'delete-quotations'],
                    ['permission_name' => 'approve quotations', 'name' => 'approve-quotations'],
                ],
            ],

            [
                'group_name' => 'Manage Deduction Types',
                'description' => 'The permissions below are used to manage deduction types',
                'permissions' => [
                    ['permission_name' => 'add deduction types', 'name' => 'add-deduction-types'],
                    ['permission_name' => 'view deduction types', 'name' => 'view-deduction-types'],
                    ['permission_name' => 'update deduction types', 'name' => 'update-deduction-types'],
                    ['permission_name' => 'delete deduction types', 'name' => 'delete-deduction-types'],

                ],
            ],

            [
                'group_name' => 'Manage Salaries',
                'description' => 'The permissions below are used to manage salaries',
                'permissions' => [
                    ['permission_name' => 'add salaries', 'name' => 'add-salaries'],
                    ['permission_name' => 'view salaries', 'name' => 'view-salaries'],
                    ['permission_name' => 'update salaries', 'name' => 'update-salaries'],
                    ['permission_name' => 'delete salaries', 'name' => 'delete-salaries'],
                ],
            ],

            [
                'group_name' => 'Manage Payroll Records',
                'description' => 'The permissions below are used to manage payroll records',
                'permissions' => [
                    ['permission_name' => 'add payroll records', 'name' => 'add-payroll-records'],
                    ['permission_name' => 'view payroll records', 'name' => 'view-payroll-records'],
                    ['permission_name' => 'update payroll records', 'name' => 'update-payroll-records'],
                    ['permission_name' => 'delete payroll records', 'name' => 'delete-payroll-records'],
                    ['permission_name' => 'process payroll records', 'name' => 'process-payroll-records'],
                    ['permission_name' => 'adjust payroll records', 'name' => 'adjust-payroll-records'],
                    ['permission_name' => 'post payroll records', 'name' => 'post-payroll-records'],
                    ['permission_name' => 'approve payroll records', 'name' => 'approve-payroll-records'],
                ],
            ],

            [
                'group_name' => 'Manage PF Numbers',
                'description' => 'The permissions below are used to manage pf numbers',
                'permissions' => [
                    ['permission_name' => 'add pfnos', 'name' => 'add-pfnos'],
                    ['permission_name' => 'view pfnos', 'name' => 'view-pfnos'],
                    ['permission_name' => 'update pfnos', 'name' => 'update-pfnos'],
                    ['permission_name' => 'delete pfnos', 'name' => 'delete-pfnos'],
                ],
            ],

            [
                'group_name' => 'Manage Office Staff',
                'description' => 'The permissions below are used to manage office staff',
                'permissions' => [
                    ['permission_name' => 'add office staff', 'name' => 'add-office-staff'],
                    ['permission_name' => 'view office staff', 'name' => 'view-office-staff'],
                    ['permission_name' => 'update office staff', 'name' => 'update-office-staff'],
                    ['permission_name' => 'delete office staff', 'name' => 'delete-office-staff'],
                ],
            ],

            [
                'group_name' => 'Manage Job Groups',
                'description' => 'The permissions below are used to manage job groups',
                'permissions' => [
                    ['permission_name' => 'add job groups', 'name' => 'add-job-groups'],
                    ['permission_name' => 'view job groups', 'name' => 'view-job-groups'],
                    ['permission_name' => 'update job groups', 'name' => 'update-job-groups'],
                    ['permission_name' => 'delete job groups', 'name' => 'delete-job-groups'],
                ],
            ],


            [
                'group_name' => 'Manage Imprest',
                'description' => 'The permissions below are used to manage imprest',
                'permissions' => [
                    ['permission_name' => 'add imprest', 'name' => 'add-imprest'],
                    ['permission_name' => 'view imprest', 'name' => 'view-imprest'],
                    ['permission_name' => 'update imprest', 'name' => 'update-imprest'],
                    ['permission_name' => 'delete imprest', 'name' => 'delete-imprest'],
                ],
            ],

            [
                'group_name' => 'Manage Audits',
                'description' => 'The permissions below are used to manage audits',
                'permissions' => [
                    ['permission_name' => 'add audits', 'name' => 'add-audits'],
                    ['permission_name' => 'view audits', 'name' => 'view-audits'],
                    ['permission_name' => 'update audits', 'name' => 'update-audits'],
                    ['permission_name' => 'delete audits', 'name' => 'delete-audits'],
                ],
            ],

            [
                'group_name' => 'Manage File Uploads',
                'description' => 'The permissions below are used to manage File Uploads',
                'permissions' => [
                    ['permission_name' => 'add File Uploads', 'name' => 'add-file-uploads'],
                    ['permission_name' => 'view File Uploads', 'name' => 'view-file-uploads'],
                    ['permission_name' => 'update File Uploads', 'name' => 'update-file-uploads'],
                    ['permission_name' => 'delete File Uploads', 'name' => 'delete-file-uploads'],
                ],
            ],
            [
                'group_name' => 'Manage Expenses',
                'description' => 'The permissions below are used to manage Expenses',
                'permissions' => [
                    ['permission_name' => 'add Expenses', 'name' => 'add-expenses'],
                    ['permission_name' => 'view Expenses', 'name' => 'view-expenses'],
                    ['permission_name' => 'update Expenses', 'name' => 'update-expenses'],
                    ['permission_name' => 'Approve Expenses', 'name' => 'approve-expenses'],
                    ['permission_name' => 'delete Expenses', 'name' => 'delete-expenses'],
                ],
            ],
            [
                'group_name' => 'Manage Supplies/Collections',
                'description' => 'The permissions below are used to manage Supplies',
                'permissions' => [
                    ['permission_name' => 'add supplies', 'name' => 'add-supplies'],
                    ['permission_name' => 'view supplies', 'name' => 'view-supplies'],
                    ['permission_name' => 'update supplies', 'name' => 'update-supplies'],
                    ['permission_name' => 'delete supplies', 'name' => 'delete-supplies'],
                ],
            ],
            [
                'group_name' => 'Manage System Reports',
                'description' => 'The permissions below are used to manage system reports',
                'permissions' => [
                    ['permission_name' => 'generate users reports', 'name' => 'generate-users-reports'],
                    ['permission_name' => 'generate departments reports', 'name' => 'generate-departments-reports'],
                    ['permission_name' => 'generate inventories reports', 'name' => 'generate-inventories-reports'],
                    ['permission_name' => 'generate clients reports', 'name' => 'generate-supplier-reports'],
                    ['permission_name' => 'generate invoices reports', 'name' => 'generate-invoices-reports'],
                    ['permission_name' => 'generate incidents reports', 'name' => 'generate-incidents-reports'],
                    ['permission_name' => 'generate deductions reports', 'name' => 'generate-deductions-reports'],
                    ['permission_name' => 'generate quotations reports', 'name' => 'generate-quotations-reports'],
                    ['permission_name' => 'generate salaries reports', 'name' => 'generate-salaries-reports'],
                    ['permission_name' => 'generate payroll records reports', 'name' => 'generate-payroll-records-reports'],
                    ['permission_name' => 'generate pfnos reports', 'name' => 'generate-pfnos-reports'],
                    ['permission_name' => 'generate office staff reports', 'name' => 'generate-office-staff-reports'],
                    ['permission_name' => 'generate audits reports', 'name' => 'generate-audits-reports'],
                    ['permission_name' => 'generate payment reports', 'name' => 'generate-payment-reports'],
                    ['permission_name' => 'generate creditors aging account reports', 'name' => 'generate-creditors-aging-account-reports'],
                    ['permission_name' => 'generate debtors aging account reports', 'name' => 'generate-debtors-aging-account-reports'],
                    ['permission_name' => 'generate creditors reconciliation reports', 'name' => 'generate-creditors-reconciliation-reports'],
                    ['permission_name' => 'generate customer statement reports', 'name' => 'generate-supplier-statement-reports'],
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
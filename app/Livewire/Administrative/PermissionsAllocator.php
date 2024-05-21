<?php

namespace App\Livewire\Administrative;

use App\Models\Role;
use Livewire\Component;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PermissionGroup;
// use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionsAllocator extends Component
{
    public $count = null;
    public $role = null;
    public $roleData = null;
    public $data = null;
    public $permissions = [];
    public $toStore = null;
    public $groups = null;
    public $newPermissions = [];
    public $oldpermissions = [];
    public $permissionsToRevoke = [];
    // public $currentRole = null;

    public function mount($role = null)
    // public function mount($role = 1)
    {
        $this->role = $role;
        if (!is_null($this->role)) {
            $this->loadData();
        }else{
           return redirect()->route('tenant-settings-roles');
        }

    }

    public function loadData()
    {
        $this->groups = PermissionGroup::getAllPermissions();
        $this->getOldPermissions();
        $this->comparePermissions();
        if (!is_null($this->role)) {
            // dd($this->role);

            Log::info("Role ID: " . $this->role);
            $this->roleData = Role::where('id', '=', $this->role)->first();
            // dd($this->roleData);
        } else {
            $this->roleData = null;
        }
    }
    public function getOldPermissions()
    {
        $permissions = DB::select("SELECT r.permission_id FROM role_has_permissions r WHERE r.role_id = $this->role");
        foreach ($permissions as $permission) {
            $this->oldpermissions[$permission->permission_id] = $permission->permission_id;
        }
        $permissions = [];
        if (!empty($this->oldpermissions)) {
            foreach ($this->oldpermissions as $old) {
                $permissions[$old] = $old;
            }
        }
        $this->permissions = $permissions;
    }
    public function togglePermission($permissionId)
    {
        dd("function togglePermission");
        //convert the permission to an integer
        $permissionId = (int) $permissionId;
        if (in_array($permissionId, $this->permissions)) {
            // Remove the permission if it exists
            $this->permissions = array_diff($this->permissions, [$permissionId]);
        } else {
            // Add the permission if it doesn't exist
            $this->permissions[] = $permissionId;
        }
        // dd($this->permissions);


        $this->comparePermissions();
        $this->syncPermissions();

        //reset all fields
        $this->loadData();
        // $this->restartPage(request());
    }
    public function restartPage($request)
    {
        $scrollPosition = $request->input('scrollPosition');

        // Redirect with scroll position as a query parameter
        return redirect()->route('settings.permissions.allocation', ['role' => $this->role, 'scrollPosition' => $scrollPosition]);
    }


    // public function updatedPermissions()
    // {
    //     dd($this->permissions);
    //     $this->comparePermissions();
    //     $this->syncPermissions();
    // }
    public function savePermissions()
    {
        $this->comparePermissions();
        $this->syncPermissions();
    }
    public function getPermissions()
    {
        return array_keys(array_filter($this->permissions));
    }

    public function comparePermissions()
    {
        $this->data = array_values(array_unique($this->permissions));
        $this->permissionsToRevoke = array_diff($this->oldpermissions, $this->permissions);
    }
    public function syncPermissions()
    {
        // dd('here');
        $permissions = $this->data;
        $role = Role::find($this->role);
        $this->revokePermissions($role);

        if (count($permissions) > 0) {
            foreach ($permissions as $permission) {
                if ($permission === '0' || $permission === false || $permission === 0) {
                    Log::info("Got Boolean");
                } else {
                    $permission_data = Permission::find($permission);
                    // Make sure to set the guard for the permission
                    if ($role->id != 1) {
                        // $permission_data->guard_name = 'sanctum';
                    }
                    // $permission_name = $permission_data->name;
                    // dd($permission_name);
                    $role->givePermissionTo($permission_data->name);
                    // $role->givePermissionTo('edit articles');
                }
            }
        }
    }
    public function revokePermissions($role)
    {
        $permissions = $this->permissionsToRevoke;
        if (count($permissions) > 0) {
            foreach ($permissions as $permission) {
                if ($permission === '0' || $permission === false || $permission === 0) {
                    Log::info("Got Boolean");
                } else {
                    $perm = Permission::find($permission);
                    // $perm->guard_name = 'web';
                    // dd($perm->name);
                    $role->revokePermissionTo($perm->name);
                    // $role->revokePermissionTo('edit articles');
                }
            }
            return redirect()->route('administrative.permissions.allocation', ['role' => $this->role]);
        }
        // $this->loadData();
        //hard-reload to get the remaining permissions
    }
    public function render()
    {
        return view('livewire.administrative.permissions-allocator');
    }
}

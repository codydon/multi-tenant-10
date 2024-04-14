<?php

namespace App\Livewire\Tenants;

use App\Models\Permission;
use App\Models\Tenant;
use Livewire\Component;

class ViewTenants extends Component
{

    public function syncPermissions($tenant_id)
    {
        dd($tenant_id);
        Permission::loopOverTenantsMigrating($tenant_id);
    }

    // public $tenants = [];
    public function render()
    {

        $tenants = Tenant::getTenants();
        return view('livewire.tenants.view-tenants', compact('tenants'));
    }
}
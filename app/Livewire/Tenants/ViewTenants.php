<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Livewire\Component;

class ViewTenants extends Component
{

    // public $tenants = [];
    public function render()
    {

        $tenants = Tenant::orderBy('id', 'asc')->get();
        // dd($tenants);
        return view('livewire.tenants.view-tenants', compact('tenants'));
    }
}
<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Livewire\Component;

class ManageTenants extends Component
{

    public $domain='';
    public $tenant_name='';

    protected $rules = [
        'domain' => 'required|string|min:3|max:20',
        'tenant_name' => 'required|string|min:3|max:20',
    ];



    public function updated($field)
    {
        $this->validateOnly($field);

        // dd($this->domain);
    }

    public function createTenant()
    {
        $this->validate();
        //check if tenant already exists
        $tenant = Tenant::where('id', $this->domain)->first();
        // dd($tenant);
        if ($tenant) {

            dd('Tenant with name ' . $this->tenant_name . ' having unique domain ' . $this->domain . ' already exists');
            // session()->flash('error', 'Tenant with name' . $this->tenant_name . ' having unique domain ' . $this->domain . ' already exists');
            return;
        }

        $tenant = Tenant::createTenants($this->domain, $this->tenant_name);
        $this->domain = '';
        $this->tenant_name = '';

        return redirect()->route('central-view-tenants');
    }
    public function render()
    {
        return view('livewire.tenants.manage-tenants');
    }
}
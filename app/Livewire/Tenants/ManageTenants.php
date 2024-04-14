<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use App\Models\Package;
use Livewire\Component;

class ManageTenants extends Component
{

    public $domain='';
    public $tenant_name='';
    public $package_id=0;

    protected $rules = [
        'domain' => 'required|string|min:3|max:20',
        'tenant_name' => 'required|string|min:3|max:20',
        'package_id' => 'required|integer',
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

        // dd($this->domain, $this->tenant_name, $this->package_id);
        $tenant = Tenant::createTenants($this->domain, $this->tenant_name, intval($this->package_id));
        $this->domain = '';
        $this->tenant_name = '';

        return redirect()->route('central-view-tenants');
    }
    public function render()
    {

        // $packages = Package::select('id as value', 'name as label')->get(); //coz bladewindul is expecting value and label
        $packages = Package::select('id', 'name')->get();

        // $pkgs = $packages->map(function ($package) {
        //     return [
        //         'label' => $package->name,
        //         'value' => $package->id,
        //     ];
        // })->toArray();
        // dd($pkgs);

        return view('livewire.tenants.manage-tenants', compact('packages'));
    }
}

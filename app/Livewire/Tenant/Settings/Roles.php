<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Role;
use Livewire\Component;

class Roles extends Component
{
    public function render()
    {
        $roles = Role::all();
        return view('livewire.tenant.settings.roles', compact('roles'));
    }
}
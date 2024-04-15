<?php

namespace App\Livewire\Dashboards;

use App\Models\Role;
use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        $roles = Role::all();
        // dd($roles);
        return view('livewire.dashboards.welcome', compact('roles'));
    }
}
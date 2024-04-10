<?php

namespace App\Livewire\Tenants;

use Livewire\Component;

class Welcome extends Component
{

    public $name = 'Livewire';


    public function updated($field)
    {
        $this->validateOnly($field, [
            'name' => 'min:6',
        ]);

        dd('updated');
    }

    public function render()
    {
        return view('livewire.tenants.welcome');
    }
}
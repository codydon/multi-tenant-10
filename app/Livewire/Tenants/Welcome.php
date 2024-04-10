<?php

namespace App\Livewire\Tenants;

use Livewire\Component;

class Welcome extends Component
{

    public $name = 'Livewire';


    public $countries = [
        ['country' => 'Benin',         'code' => 'bj'],
        ['country' => 'Burkina Faso',  'code' => 'bf'],
        ['country' => 'Cameroon',      'code' => 'cm'],
        ['country' => 'Congo',         'code' => 'cd'],
        ['country' => 'Gambia',        'code' => 'gm'],
        ['country' => 'Ghana',         'code' => 'gh'],
        ['country' => 'Ivory Coast',   'code' => 'ci'],
        ['country' => 'Nigeria',       'code' => 'ng'],
        ['country' => 'Kenya',         'code' => 'ke'],
        ['country' => 'Togo',          'code' => 'tg'],
    ];


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
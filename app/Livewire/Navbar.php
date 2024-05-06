<?php

namespace App\Livewire;

use Livewire\Component;

class Navbar extends Component
{

    public $links = [
        [
            'name' => 'Events',
            'route' => 'welcome',
            'active' => false
        ],
        [
            'name' => 'Speakers',
            'route' => 'tenant-settings-roles',
            'active' => false
        ],
        [
            'name' => 'News & Updates',
            'route' => 'tenant-settings-roles',
            'active' => false
        ],
        [
            'name' => 'Contact Us',
            'route' => 'administrative-permissions-allocator',
            'active' => false
        ],
    ];

    public function render()
    {
        return view('livewire.navbar');
    }
}
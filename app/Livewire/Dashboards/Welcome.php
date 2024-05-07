<?php

namespace App\Livewire\Dashboards;

use App\Models\Role;
use Livewire\Component;

class Welcome extends Component
{
    public $days = [
        [
            'title' => 'Day 1',
            'date' => '2021-10-01',
            'slug' => 'day-1',
        ],
        [
            'title' => 'Day 2',
            'date' => '2021-10-02',
            'slug' => 'day-2',
        ],
        [
            'title' => 'Day 3',
            'date' => '2021-10-03',
            'slug' => 'day-3',
        ],
        [
            'title' => 'Day 4',
            'date' => '2021-10-04',
            'slug' => 'day-4',
        ],
        [
            'title' => 'Day 5',
            'date' => '2021-10-05',
            'slug' => 'day-5',
        ],
    ];



    public function render()
    {
        $roles = Role::all();
        // dd($roles);
        return view('livewire.dashboards.welcome', compact('roles'));
    }
}
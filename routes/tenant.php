<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Livewire\Administrative\PermissionsAllocator;
use App\Livewire\Dashboards\Welcome;
use App\Livewire\Tenant\Settings\Roles;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Route::get('/', function () {
    //     dd(\App\Models\User::all());
    //     return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    // });

    Route::get('/products', function () {
        dd(\App\Models\Products::all());
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::get('/', Welcome::class)->name('welcome');
    Route::get('/settings/roles', Roles::class)->name('tenant-settings-roles');
    Route::get('/permissions-allocator/{role?}', PermissionsAllocator::class)->name('administrative-permissions-allocator');
});
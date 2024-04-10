<?php

use App\Livewire\Tenants\Welcome;
use App\Livewire\Tenants\ViewTenants;
use Illuminate\Support\Facades\Route;
use App\Livewire\Tenants\ManageTenants;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/home', function () {
//     return view('welcome');
// });

Route::get('/home', Welcome::class)->name('central-home');
Route::get('/central-view-tenants', ViewTenants::class)->name('central-view-tenants');
Route::get('/central-manage-tenants', ManageTenants::class)->name('central-manage-tenants');
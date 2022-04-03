<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('', 'welcome');

Route::middleware(['auth:sanctum', 'verified'])->prefix('/dashboard')->group(function () {
    Route::any('truelayer/callback', Controllers\TrueLayerController::class);

    Route::get('budget/create', \App\Http\Livewire\Budget::class);

    Route::resource('provider', Controllers\ProviderController::class);
    Route::resource('account', Controllers\AccountController::class);
    Route::resource('budget', Controllers\BudgetController::class)->except('create');
    Route::resource('report', Controllers\ReportController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

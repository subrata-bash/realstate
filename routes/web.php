<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
// Admin Route
Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');
Route::get('/admin/profile', [AdminController::class, 'adminProfile'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.profile');
Route::get('/admin/login', [AdminController::class, 'adminLogin']);
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.logout');
Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.profile.store');
Route::get('/admin/change/password', [AdminController::class, 'adminChangePassword'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.change.password');
Route::post('/admin/update/password', [AdminController::class, 'adminUpdatePassword'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.update.password');

// Agent Route
Route::get('/agent/dashboard', [AgentController::class, 'agentDashboard'])
    ->middleware(['auth', 'role:agent'])
    ->name('agent.dashboard');

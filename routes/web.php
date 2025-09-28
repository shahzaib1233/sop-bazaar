<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Roles_Permissions\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminController::class, 'Dashboard'])->name('admin.dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/permissions/create', [PermissionsController::class, 'create'])->name('admin.permissions.create');
    Route::post('/permissions/store', [PermissionsController::class, 'store'])->name('admin.permissions.store');
    Route::get('/permissions/index', [PermissionsController::class, 'index'])->name('admin.permissions.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/permissions/create', [PermissionsController::class, 'create'])->name('permissions.create');
    // Route::post('/permissions/store', [PermissionsController::class, 'store'])->name('permissions.store');
    // Route::get('/permissions/index', [PermissionsController::class, 'index'])->name(name: 'permissions.index');

});

require __DIR__.'/auth.php';

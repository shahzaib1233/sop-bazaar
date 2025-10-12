<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\categories\categoriesController;
use App\Http\Controllers\admin\User\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Roles_Permissions\PermissionsController;
use App\Http\Controllers\Roles_Permissions\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::get('/dashboard', [AdminController::class, 'Dashboard'])->name('admin.dashboard');
        // Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        // Route::get('/permissions/create', [PermissionsController::class, 'create'])->name('admin.permissions.create');
        // Route::post('/permissions/store', [PermissionsController::class, 'store'])->name('admin.permissions.store');
        // Route::get('/permissions/index', [PermissionsController::class, 'index'])->name('admin.permissions.index');
        // Route::delete('/permissions/delete/{id}', [PermissionsController::class, 'destroy'])->name('admin.permissions.delete');
        // Route::get('/permissions/edit/{id}', [PermissionsController::class, 'edit'])->name('admin.permissions.edit');
        // Route::patch('/permissions/update/{id}', [PermissionsController::class, 'update'])->name('admin.permissions.update');

        //permissions routes    
          Route::group(['prefix' => 'permissions'], function () {
            Route::get('/index', [PermissionsController::class, 'index'])->name('admin.permissions.index');
            Route::get('/create', [PermissionsController::class, 'create'])->name('admin.permissions.create');
            Route::post('/store', [PermissionsController::class, 'store'])->name('admin.permissions.store');
            Route::delete('/delete/{id}', [PermissionsController::class, 'destroy'])->name('admin.permissions.delete');
            Route::get('/edit/{id}', [PermissionsController::class, 'edit'])->name('admin.permissions.edit');
            Route::patch('/update/{id}', [PermissionsController::class, 'update'])->name('admin.permissions.update');
        });



        // roles routes
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/index', [RoleController::class, 'index'])->name('admin.roles.index');
            Route::get('/create', [RoleController::class, 'create'])->name('admin.roles.create');
            Route::post('/store', [RoleController::class, 'store'])->name('admin.roles.store');
            Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('admin.roles.delete');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.roles.edit');
            Route::patch('/update/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
        });


          // users routes
        Route::group(['prefix' => 'users'], function () {
            Route::get('/index', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
            Route::post('/store', [UserController::class, 'store'])->name('admin.users.store');
            // Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
            // Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.roles.edit');
            Route::patch('/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
        });


        Route::group(['prefix' => 'categories'], function () {
            Route::get('/index', [CategoriesController::class, 'index'])->name('admin.categories.index');
            Route::get('/create', [CategoriesController::class, 'create'])->name('admin.categories.create');
            Route::post('/store', [CategoriesController::class, 'store'])->name('admin.categories.store');
            Route::delete('/delete/{id}', [CategoriesController::class, 'destroy'])->name('admin.categories.delete');
            Route::get('/edit/{id}', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
            Route::patch('/update/{id}', [CategoriesController::class, 'update'])->name('admin.categories.update');
        });
    });

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

<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\AdministratorsController;

Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::get('/verified', [App\Http\Controllers\HomeController::class, 'verified'])->name('verified');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/name-email', [ProfileController::class, 'updateNameAndEmail'])->name('profile.updateNameAndEmail');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
Route::post('/profile/user-password', [ProfileController::class, 'updateUserAndPassword'])->name('profile.updateUserAndPassword');


Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users/create', [AdministratorsController::class, 'create'])->name('users.create');
    Route::get('users', [AdministratorsController::class, 'index'])->name('users.index');
    Route::post('users', [AdministratorsController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [AdministratorsController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdministratorsController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdministratorsController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', SuperAdminMiddleware::class])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::resource('admin/users', AdministratorsController::class)->names('admin.users');

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdministratorsController::class);
});

Route::middleware(['auth', SuperAdminMiddleware::class])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::resource('users', UserController::class);
});
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // ...
    Route::delete('users/{user}', [AdministratorsController::class, 'destroy'])->name('users.destroy');
});
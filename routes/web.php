<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('welcome');
// });


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin', [App\Http\Controllers\AdministratorsController::class, 'index'])->name('admin.index');
Route::get('/super', [App\Http\Controllers\AdministratorsController::class, 'indexSuper'])->name('super.index');
Route::get('/verified', [App\Http\Controllers\HomeController::class, 'verified'])->name('verified');
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/name-email', [App\Http\Controllers\ProfileController::class, 'updateNameAndEmail'])->name('profile.updateNameAndEmail');
Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
Route::post('/profile/user-password', [App\Http\Controllers\ProfileController::class, 'updateUserAndPassword'])->name('profile.updateUserAndPassword');
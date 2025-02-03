<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'home'])->name('home');


Auth::routes(['verify' => true]);

Route::get('/settings', [HomeController::class, 'settings'])->name('settings');
Route::get('/verify', [HomeController::class, 'verification'])->name('verify');

Route::put('/settings/user/{user}/name', [SettingsController::class, 'updateName'])->name('user.updateName');
Route::put('/settings/user/{user}/password', [SettingsController::class, 'updatePassword'])->name('user.updatePassword');

Route::resource('/user', UserController::class);


Route::get('/admin', [AdministradorController::class,'index'])->name('admin');

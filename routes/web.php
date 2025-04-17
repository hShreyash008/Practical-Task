<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Auth;
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
    return view('layouts.app');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('admin');
Route::get('register/customer', [RegisterController::class, 'showCustomerRegisterForm'])->name('register.customer');
Route::post('register/customer', [RegisterController::class, 'registerCustomer'])->name('register.customer.submit');

Route::get('register/admin', [RegisterController::class, 'showAdminRegisterForm'])->name('register.admin');
Route::post('register/admin', [RegisterController::class, 'registerAdmin'])->name('register.admin.submit');

Route::get('verify', [RegisterController::class, 'showVerificationForm'])->name('verification.form');
Route::post('verify', [RegisterController::class, 'verifyCode'])->name('verify.code');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

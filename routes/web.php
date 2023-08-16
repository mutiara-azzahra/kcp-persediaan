<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Auth;

use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PembelianAOPController;
use App\Http\Controllers\UploadDBPController;
use App\Http\Controllers\DBPController;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/pembelian-aop', [PembelianAOPController::class, 'index'])->name('pembelian-aop.index');
    Route::get('/pembelian-aop/proses', [PembelianAOPController::class, 'proses'])->name('pembelian-aop.proses');
    Route::get('/pembelian-aop/prosesPersediaan', [PembelianAOPController::class, 'prosesPersediaan'])->name('pembelian-aop.prosesPersediaan');

    Route::get('/upload-dbp', [UploadDBPController::class, 'index'])->name('upload-dbp.index');
    Route::post('/upload-dbp', [UploadDBPController::class, 'uploadDbp'])->name('upload-dbp.uploadDbp');

    Route::get('/dbp', [DBPController::class, 'index'])->name('dbp.index');
    Route::post('/dbp', [DBPController::class, 'upload'])->name('dbp.upload');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/create', [UserController::class, 'store'])->name('user.store');
});

Route::get('/login', [LoginController::class, 'formLogin'])->name('login.formLogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [LoginController::class, 'formRegister'])->name('login.formRegister');

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');





<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembelianAOPController;
use App\Http\Controllers\UploadDBPController;
use App\Http\Controllers\StockController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/pembelian-aop', [PembelianAOPController::class, 'index'])->name('pembelian-aop.index');
Route::get('/pembelian-aop/proses', [PembelianAOPController::class, 'proses'])->name('pembelian-aop.proses');
Route::get('/pembelian-aop/prosesPersediaan', [PembelianAOPController::class, 'prosesPersediaan'])->name('pembelian-aop.prosesPersediaan');

Route::get('/upload-dbp', [UploadDBPController::class, 'index'])->name('upload-dbp.index');
Route::post('/upload-dbp', [UploadDBPController::class, 'uploadDbp'])->name('upload-dbp.uploadDbp');

Route::get('/dbp', [DBPController::class, 'index'])->name('dbp.index');
Route::post('/dbp', [DBPController::class, 'upload'])->name('dbp.upload');



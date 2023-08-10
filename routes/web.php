<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembelianAOPController;
use App\Http\Controllers\UploadDBPController;
use App\Http\Controllers\StockController;


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
Route::get('/pembelian-aop/upload-surat', [PembelianAOPController::class, 'formUploadSurat'])->name('pembelian-aop.formUploadSurat');
Route::post('/pembelian-aop/upload-surat', [PembelianAOPController::class, 'uploadSurat'])->name('pembelian-aop.uploadSurat');
Route::get('/pembelian-aop/upload-rekap', [PembelianAOPController::class, 'formUploadRekap'])->name('pembelian-aop.formUploadRekap');
Route::post('/pembelian-aop/upload-rekap', [PembelianAOPController::class, 'uploadRekap'])->name('pembelian-aop.uploadRekap');
Route::get('/pembelian-aop/proses', [PembelianAOPController::class, 'proses'])->name('pembelian-aop.proses');
Route::get('/pembelian-aop/prosesPersediaan', [PembelianAOPController::class, 'prosesPersediaan'])->name('pembelian-aop.prosesPersediaan');
Route::get('/pembelian-aop/tampil', [PembelianAOPController::class, 'tampil'])->name('pembelian-aop.tampil');


Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

Route::get('/upload-dbp', [UploadDBPController::class, 'index'])->name('upload-dbp.index');
Route::post('/upload-dbp', [UploadDBPController::class, 'uploadDbp'])->name('upload-dbp.uploadDbp');



<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatStokController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class)
        ->except('show')
        ->middlewareFor(['edit', 'update', 'destroy'], 'check.role:admin');
    Route::resource('barang', BarangController::class)
        ->except('show')
        ->middlewareFor('destroy', 'check.role:admin');
    Route::get('/stok/masuk', [StokMasukController::class, 'create'])->name('stok.masuk');
    Route::post('/stok/masuk', [StokMasukController::class, 'store'])->name('stok.masuk.store');
    Route::get('/stok/keluar', [StokKeluarController::class, 'create'])->name('stok.keluar');
    Route::post('/stok/keluar', [StokKeluarController::class, 'store'])->name('stok.keluar.store');
    Route::get('/stok/riwayat', [RiwayatStokController::class, 'index'])->name('stok.riwayat');
    Route::get('/laporan/stok-menipis', [LaporanController::class, 'stokMenipis'])->name('laporan.stok-menipis');
    Route::get('/laporan/mutasi-stok', [LaporanController::class, 'mutasiStok'])->name('laporan.mutasi-stok');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('check.role:admin')->group(function () {
        Route::resource('user', UserController::class)
            ->except(['show', 'destroy']);
    });
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BangkomController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/bangkom/{bangkom}/download-docx', [BangkomController::class, 'downloadDocx'])->name('bangkom.downloadDocx');
Route::get('/bangkom/{bangkom}/download-sttp', [BangkomController::class, 'downloadSttp'])->name('bangkom.downloadSttp');
Route::get('/bangkom/{bangkom}/download-permohonan', [BangkomController::class, 'downloadPermohonan'])->name('bangkom.downloadPermohonan');
Route::get('/bangkom/{bangkom}/kelengkapan-dokumen', [BangkomController::class, 'kelengkapanDokumen'])->name('bangkom.kelengkapanDokumen');
Route::get('/bangkom/{bangkom}/dokumentasi', [BangkomController::class, 'dokumentasi'])->name('bangkom.dokumentasi');
Route::get('/bangkom/{bangkom}/peserta', [BangkomController::class, 'peserta'])->name('bangkom.peserta');


require __DIR__.'/auth.php';

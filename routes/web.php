<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BangkomController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bangkom/{bangkom}/download-docx', [BangkomController::class, 'downloadDocx'])->name('bangkom.downloadDocx');
Route::get('/bangkom/{bangkom}/download-permohonan', [BangkomController::class, 'downloadPermohonan'])->name('bangkom.downloadPermohonan');
Route::get('/bangkom/{bangkom}/download-sttp', [BangkomController::class, 'downloadSttp'])->name('bangkom.downloadSttp');
Route::get('/bangkom/{bangkom}/kelengkapan-dokumen', [BangkomController::class, 'kelengkapanDokumen'])->name('bangkom.kelengkapanDokumen');
Route::get('/bangkom/{bangkom}/dokumentasi', [BangkomController::class, 'dokumentasi'])->name('bangkom.dokumentasi');
Route::get('/bangkom/{bangkom}/peserta', [BangkomController::class, 'peserta'])->name('bangkom.peserta');

Route::get('/admin/categories/create', \App\Livewire\Admin\Page\Categories\Create::class)->name('categories.create');
Route::get('/admin/categories', function () {
    return view('categories.index'); // Assuming you have a categories index view
})->name('categories');

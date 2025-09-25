<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BangkomController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bangkom/{bangkom}/download-docx', [BangkomController::class, 'downloadDocx'])->name('bangkom.downloadDocx');

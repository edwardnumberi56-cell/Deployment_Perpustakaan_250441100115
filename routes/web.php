<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

// 1. Beranda
Route::get('/', function () {
    return view('home');
})->name('home');

// 2. Daftar Buku
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// 3. Detail Buku
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');   
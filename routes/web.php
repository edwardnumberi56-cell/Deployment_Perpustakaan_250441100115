<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Api\BukuController as ApiBukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Daftar Buku (semua role yang login)
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

    // A3: CSRF - Route GET hapus buku (rentan CSRF, hanya di balik auth)
    Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy'])->name('buku.hapus');

    // A4: IDOR - Route GET detail peminjaman tanpa cek kepemilikan
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // KHUSUS ADMIN & PETUGAS: Manajemen Buku
    Route::middleware(['role:admin|petugas'])->group(function () {
        Route::get('/buku/create', [ApiBukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [ApiBukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{id}/edit', [ApiBukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{id}', [ApiBukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{id}', [ApiBukuController::class, 'destroy'])->name('buku.destroy');

        // Peminjaman
        Route::resource('peminjaman', PeminjamanController::class)->except(['show']);

        // Admin Peminjaman
        Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::put('/admin/peminjaman/{id}/kembali', [PeminjamanController::class, 'kembalikan'])->name('admin.peminjaman.kembali');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // KHUSUS ADMIN: Kelola Pengguna
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    });

    // KHUSUS SISWA
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('siswa.peminjaman.riwayat');
        
        // TAMBAHKAN INI: Rute detail yang nanti ID-nya bisa dimanipulasi untuk uji A4
        Route::get('/peminjaman/{id}/detail', [PeminjamanController::class, 'show'])->name('siswa.peminjaman.detail');
        
        Route::post('/buku/{id}/pinjam', [PeminjamanController::class, 'storeSiswa'])->name('siswa.pinjam');
    });

    Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('laporan.rekap');
    });

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Rute khusus untuk mengembalikan hasil pencarian dalam bentuk JSON
    Route::get('/buku-cari', [BukuController::class, 'cari'])->middleware('auth');

require __DIR__.'/auth.php';
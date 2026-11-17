<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        // Mengambil seluruh data peminjaman untuk ditampilkan sebagai laporan
        $laporans = Peminjaman::with(['anggota', 'buku'])->get();
        return view('laporan.index', compact('laporans'));
    }

    public function show(Peminjaman $peminjaman)
    {
        // Memastikan policy 'view' berjalan untuk menutup celah keamanan
        Gate::authorize('view', $peminjaman);

        return view('laporan.show', compact('peminjaman'));
    }

    // Tambahkan method cetak ini untuk mengatasi error 500
    public function cetak()
    {
        // Mengambil data peminjaman beserta relasinya untuk dicetak
        $laporans = Peminjaman::with(['anggota', 'buku'])->get();
        
        // Mengarahkan ke file view khusus cetak
        return view('laporan.cetak', compact('laporans'));
    }

    
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;

class SiswaPeminjamanController extends Controller
{
    public function store(Request $request, $bukuId)
    {
        Peminjaman::create([
            'anggota_id' => auth()->user()->id,
            'buku_id' => $bukuId,
            'tanggal_pinjam' => now(),
        ]);

        // Ubah arah redirect ke halaman daftar buku atau riwayat siswa
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dipinjam!');
    }
}

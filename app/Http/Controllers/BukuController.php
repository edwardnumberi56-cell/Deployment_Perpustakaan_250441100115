<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; 
use Illuminate\Support\Facades\DB; // Tambahkan ini di bagian atas

class BukuController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input parameter pencarian dari user
        $keyword = $request->input('search');

        // RENTAN SQLi: Menggunakan DB::select dengan string interpolation
        if ($keyword) {
            $bukus = DB::select("SELECT * FROM bukus WHERE judul LIKE '%$keyword%'");
        } else {
            $bukus = DB::select("SELECT * FROM bukus");
        }

        return view('buku.index', compact('bukus'));
    }

    public function cari(Request $request)
{
    // Membaca query string 'judul'
    $judul = $request->input('judul');

    // RENTAN SQLi: Menggunakan DB::select dengan string interpolation
    if ($judul) {
        $bukus = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
    } else {
        $bukus = DB::select("SELECT * FROM bukus");
    }

    // Mengembalikan hasilnya sebagai format JSON
    return response()->json($bukus);
}

    // A3: Method destroy untuk menghapus buku via GET (rentan CSRF)
    public function destroy($buku)
    {
        $bukuModel = Buku::findOrFail($buku);
        $bukuModel->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    // RENTAN A4: Mengambil data berdasarkan ID tanpa mengecek hak akses / kepemilikan
    public function edit($id)
    {
        // Sengaja dibuat rentan A4 tanpa cek otorisasi peran
        $buku = DB::select("SELECT * FROM bukus WHERE id = ?", [$id]);
        return view('buku.edit', compact('buku'));
    }
}
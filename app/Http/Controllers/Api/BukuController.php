<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku beserta pencarian, filter, pagination, dan withCount.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->withCount('peminjamans');

        // Fitur Pencarian berdasarkan judul atau penulis
        if ($request->has('cari') && !empty($request->cari)) {
            $cari = $request->cari;
            $query->where(function($q) use ($cari) {
                $q->where('judul', 'like', '%' . $cari . '%')
                  ->orWhere('penulis', 'like', '%' . $cari . '%');
            });
        }

        // Fitur Filter berdasarkan kategori_id
        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->where('kategori_id', $request->kategori);
        }

        // Fitur Filter berdasarkan tahun_terbit
        if ($request->has('tahun') && !empty($request->tahun)) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Urutkan dari data terbaru dan terapkan pagination (10 data per halaman)
        $buku = $query->latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar buku berhasil diambil',
            'data' => $buku
        ], 200);
    }

    /**
     * Menyimpan data buku baru.
     */
    public function store(StoreBukuRequest $request)
    {
    // Menggunakan data yang sudah divalidasi oleh StoreBukuRequest
    $buku = Buku::create($request->validated());

    return response()->json([
        'status' => 'success',
        'message' => 'Buku berhasil ditambahkan',
        'data' => $buku->load('kategori')
    ], 201);
}

    /**
     * Menampilkan detail buku beserta relasi kategori dan peminjaman.
     */
    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);

        if (!$buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail buku berhasil ditemukan',
            'data' => $buku
        ], 200);
    }

    /**
     * Memperbarui data buku.
     */
    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        $buku->update($request->validated());
        $buku->load('kategori');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ], 200);
    }

    /**
     * Menghapus data buku.
     */
    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }

    /**
     * Rekapitulasi jumlah buku per kategori.
     */
    public function rekapBukuPerKategori()
    {
        $rekap = Kategori::withCount('bukus')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Rekapitulasi jumlah buku per kategori berhasil diambil',
            'data' => $rekap
        ], 200);
    }

    /**
     * Rekapitulasi total peminjaman per buku.
     */
    public function rekapPeminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjamans')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Rekapitulasi total peminjaman per buku berhasil diambil',
            'data' => $rekap
        ], 200);
    }
}
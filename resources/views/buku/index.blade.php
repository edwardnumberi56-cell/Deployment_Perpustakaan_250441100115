@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Manajemen Data Buku (RESTful API)</h2>

    {{-- Pesan Notifikasi Sukses / Error --}}
    <div id="alert-container" class="mt-3"></div>

    <div class="row mt-3">
        {{-- Kolom Formulir (Tambah & Ubah) --}}
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white" id="form-title">Tambah Buku Baru</div>
                <div class="card-body">
                    <form id="buku-form">
                        <input type="hidden" id="buku-id">
                        
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">ID Kategori</label>
                            <input type="number" class="form-control" id="kategori_id" required>
                            <div class="invalid-feedback" id="error-kategori_id"></div>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="judul" required>
                            <div class="invalid-feedback" id="error-judul"></div>
                        </div>

                        <div class="mb-3">
                            <label for="penulis" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="penulis" required>
                            <div class="invalid-feedback" id="error-penulis"></div>
                        </div>

                        <div class="mb-3">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahun_terbit" required>
                            <div class="invalid-feedback" id="error-tahun_terbit"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="btn-submit">Simpan</button>
                        <button type="button" class="btn btn-secondary w-100 mt-2 d-none" id="btn-cancel" onclick="resetForm()">Batal</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom Tabel Data Buku --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Daftar Buku Perpustakaan</div>
                <div class="card-body">
                    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 30%;">Judul Buku</th>
                <th style="width: 20%;">Penulis</th>
                <th style="width: 10%;">Tahun</th>
                <th style="width: 15%;" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="buku-table-body">
            <!-- Data dari JavaScript akan dirender di sini secara otomatis -->
        </tbody>
    </table>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Skrip JavaScript Fetch API, async/await, dan Penanganan Error --}}
<script>
    const API_URL = '/api/buku';

    // 1. Menampilkan Data Buku (GET) dan merendernya ke tabel
async function loadBuku() {
    try {
        let response = await fetch(API_URL);
        if (!response.ok) throw new Error('Gagal mengambil data dari server.');

        let result = await response.json();
        console.log("Data dari API:", result);
        
        // MENYESUAIKAN DENGAN BUKURESOURCE COLLECTION & PAGINATION
        let data = [];
        if (result.data && Array.isArray(result.data)) {
            data = result.data; // Format utama BukuResource::collection
        } else if (result.data && result.data.data && Array.isArray(result.data.data)) {
            data = result.data.data; 
        } else if (Array.isArray(result)) {
            data = result;
        }

        let tbody = document.getElementById('buku-table-body');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center">Belum ada data buku.</td></tr>`;
            return;
        }

       data.forEach((buku, index) => {
    // Mengambil data dengan mendukung format baru (BukuResource) maupun format lama
    let kategori = buku.kategori?.nama_kategori ?? buku.kategori?.nama ?? buku.kategori_id ?? '-';
    let judul = buku.judul_buku ?? buku.judul ?? '-';
    let penulis = buku.penulis ?? '-';
    let tahun = buku.tahun ?? buku.tahun_terbit ?? '-';

    tbody.innerHTML += `
    <tr>
        <td>${index + 1}</td>
        <td>${kategori}</td>
        <td>${judul}</td>
        <td>${penulis}</td>
        <td>${tahun}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-warning btn-sm text-white" onclick="editBuku(${buku.id}, '${buku.kategori_id ?? ''}', '${buku.judul_buku ?? buku.judul ?? ''}', '${buku.penulis ?? ''}', ${buku.tahun ?? buku.tahun_terbit ?? 0})">Ubah</button>
                <button class="btn btn-danger btn-sm" onclick="deleteBuku(${buku.id})">Hapus</button>
            </div>
        </td>
    </tr>
`;
});
    } catch (error) {
        console.error('Kesalahan:', error);
    }
}
    

    // 2. Fungsi Reset Form Input
    function resetForm() {
        let form = document.getElementById('buku-form');
        if (form) form.reset();
        let bukuId = document.getElementById('buku-id');
        if (bukuId) bukuId.value = '';
    }

    // 3. Fungsi Mengisi Form saat tombol Ubah diklik
    function editBuku(id, kategori_id, judul, penulis, tahun_terbit) {
        document.getElementById('buku-id').value = id;
        document.getElementById('kategori_id').value = kategori_id;
        document.getElementById('judul').value = judul;
        document.getElementById('penulis').value = penulis;
        document.getElementById('tahun_terbit').value = tahun_terbit;
    }

    // 4. Fungsi Hapus Buku (DELETE) langsung dari web
    async function deleteBuku(id) {
        if (!confirm('Apakah kamu yakin ingin menghapus buku ini?')) return;

        try {
            let response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            let result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Gagal menghapus data.');

            loadBuku(); // Refresh tabel otomatis setelah data dihapus
        } catch (error) {
            alert('Kesalahan: ' + error.message);
        }
    }

    // 5. Proses Simpan / Perbarui Data (POST / PUT) tanpa reload halaman
    // Proses Simpan / Perbarui Data (POST / PUT)
document.getElementById('buku-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    let id = document.getElementById('buku-id').value;
    let kategori_id = document.getElementById('kategori_id').value;
    let judul = document.getElementById('judul').value;
    let penulis = document.getElementById('penulis').value;
    let tahun_terbit = parseInt(document.getElementById('tahun_terbit').value);

    // Validasi frontend: Cek apakah tahun lebih dari 2026
    if (tahun_terbit > 2026) {
        alert('Validasi Gagal:\nTahun terbit tidak boleh lebih dari tahun 2026!');
        return; // Hentikan proses simpan
    }

    // Konfirmasi khusus saat mengubah data (mode Ubah)
    if (id) {
        if (!confirm('Apakah kamu yakin ingin memperbarui data buku ini?')) {
            return;
        }
    }

    let payload = { kategori_id, judul, penulis, tahun_terbit };
    let url = id ? `${API_URL}/${id}` : API_URL;
    let method = id ? 'PUT' : 'POST';

    try {
        let response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(payload)
        });

        let result = await response.json();

        // Tangkap error validasi dari Laravel (Status 422)
        if (response.status === 422) {
            let errorMessages = '';
            for (let field in result.errors) {
                errorMessages += `- ${result.errors[field][0]}\n`;
            }
            alert("Validasi Gagal:\n" + errorMessages);
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Terjadi kesalahan saat menyimpan data.');
        }

        resetForm();
        loadBuku();

    } catch (error) {
        alert('Kesalahan: ' + error.message);
    }
});
    // Jalankan loadBuku otomatis saat halaman pertama kali dibuka
    document.addEventListener('DOMContentLoaded', loadBuku);
</script>
@endsection
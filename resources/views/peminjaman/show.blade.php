@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Peminjaman #{{ $peminjaman->id }}</span>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    {{-- A4: Data peminjaman ditampilkan tanpa cek kepemilikan (IDOR) --}}
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 35%">ID Peminjaman</th>
                            <td>{{ $peminjaman->id }}</td>
                        </tr>
                        <tr>
                            <th>Nama Peminjam</th>
                            <td>{{ $peminjaman->anggota->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email Peminjam</th>
                            <td>{{ $peminjaman->anggota->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Judul Buku</th>
                            <td>{{ $peminjaman->buku->judul ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $peminjaman->tanggal_pinjam }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>{{ $peminjaman->tanggal_kembali ?? 'Belum dikembalikan' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $peminjaman->status === 'Dikembalikan' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $peminjaman->status ?? 'Dipinjam' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


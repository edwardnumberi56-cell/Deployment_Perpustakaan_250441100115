@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-lg font-semibold mb-4">Kelola Data Peminjaman & Pengembalian Buku</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($peminjamans ?? [] as $p)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->anggota->name ?? $p->anggota->nama ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->buku->judul_buku ?? $p->buku->judul ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->tanggal_pinjam }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->tanggal_kembali ?? 'Belum Dikembalikan' }}</td>
                                
                                <!-- Kolom Status dengan Badge -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($p->tanggal_kembali)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Dikembalikan</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Dipinjam</span>
                                    @endif
                                </td>

                                <!-- Kolom Aksi / Tombol Proses -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Sesuaikan route aksi Anda jika ada -->
                                    <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data peminjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
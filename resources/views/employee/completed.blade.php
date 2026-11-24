@extends('layouts.app')
@section('title', 'Kegiatan Selesai')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Riwayat Karyawan</span>
        <h1>Kegiatan Selesai</h1>
        <p>Daftar kegiatan yang sudah Anda kerjakan dan kirim laporannya.</p>
    </div>
    <div class="page-actions"><a class="button primary" href="{{ route('employee.report') }}"><i class="bx bx-plus"></i> Kirim Laporan</a></div>
</div>

<section class="card panel">
    <div class="card-header"><h2>Riwayat laporan</h2><span class="badge-info badge">{{ $laporan->count() }} laporan</span></div>
    <div class="table-toolbar">
        <div class="table-search"><i class="bx bx-search"></i><input type="search" data-table-target="#completedTable" value="{{ request('q') }}" placeholder="Cari kegiatan atau status..."></div>
    </div>
    <div class="table-wrap">
        <table id="completedTable">
            <thead><tr><th>No</th><th>Nama Kegiatan</th><th>Tanggal Kegiatan</th><th>Lokasi</th><th>Tanggal Kirim</th><th>Status</th><th>Lampiran</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($laporan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $item->kegiatan?->nama_kegiatan ?: 'Kegiatan dihapus' }}</strong></td>
                    <td>{{ $item->kegiatan?->tanggal_kegiatan?->format('d M Y') ?: '—' }}</td>
                    <td>{{ $item->kegiatan?->lokasi_kegiatan ?: '—' }}</td>
                    <td>{{ $item->dibuat_pada?->format('d M Y H:i') ?: '—' }}</td>
                    <td>
                        <span class="badge {{ $item->status === 'Ditolak' ? 'badge-danger' : (in_array($item->status, ['Perlu Diperbaiki', 'perlu_diperbaiki']) ? 'badge-warning' : 'badge-success') }}">{{ $item->status }}</span>
                        @if($item->catatan_admin)<small class="table-note">Catatan: {{ $item->catatan_admin }}</small>@endif
                    </td>
                    <td>@if($item->lampiran)<a href="{{ route('reports.attachment', $item) }}" target="_blank" rel="noopener">Lihat lampiran</a>@else—@endif</td>
                    <td>
                        <div class="account-actions">
                            <a class="button quiet button-sm" href="{{ route('employee.reports.edit', $item) }}"><i class="bx bx-edit"></i> Ubah</a>
                            <form method="post" action="{{ route('employee.reports.destroy', $item) }}" onsubmit="return confirm('Hapus laporan ini secara permanen?')">
                                @csrf @method('delete')
                                <button class="button danger button-sm"><i class="bx bx-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty">Belum ada kegiatan selesai. Kegiatan akan tercatat setelah Anda mengirim laporan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@extends('layouts.app')
@section('title', 'Dashboard Karyawan')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Ruang Karyawan</span>
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ $petugas->nama }}. Pantau laporan yang sudah Anda kirim di sini.</p>
    </div>
    <div class="page-actions"><a class="button primary" href="{{ route('employee.report') }}"><i class="bx bx-plus"></i> Kirim Laporan</a></div>
</div>

<section class="stats-grid">
    <article class="stat">
        <span class="stat-icon info"><i class="bx bx-calendar-check"></i></span>
        <div><span class="stat-label">Kegiatan Dilaporkan</span><strong class="stat-value">{{ $totalKegiatan }}</strong><span class="stat-hint">Kegiatan yang Anda laporkan</span></div>
    </article>
    <article class="stat">
        <span class="stat-icon success"><i class="bx bx-file-blank"></i></span>
        <div><span class="stat-label">Laporan Terkirim</span><strong class="stat-value">{{ $totalLaporan }}</strong><span class="stat-hint">Total laporan Anda</span></div>
    </article>
    <article class="stat">
        <span class="stat-icon warning"><i class="bx bx-time-five"></i></span>
        <div><span class="stat-label">Menunggu Verifikasi</span><strong class="stat-value">{{ $menungguVerifikasi }}</strong><span class="stat-hint">Sedang diperiksa admin</span></div>
    </article>
</section>

<section class="card panel">
    <div class="card-header">
        <h2>Laporan Terbaru</h2>
        <a class="button quiet button-sm" href="{{ route('employee.completed') }}">Lihat semua</a>
    </div>
    <div class="table-wrap">
        <table id="employeeRecentTable">
            <thead><tr><th>Kegiatan</th><th>Tanggal Kirim</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($laporanTerbaru as $item)
                <tr>
                    <td><strong>{{ $item->kegiatan?->nama_kegiatan ?: 'Kegiatan dihapus' }}</strong></td>
                    <td>{{ $item->dibuat_pada?->format('d M Y H:i') ?: '—' }}</td>
                    <td><span class="badge {{ $item->status === 'Ditolak' ? 'badge-danger' : (in_array($item->status, ['Perlu Diperbaiki', 'perlu_diperbaiki']) ? 'badge-warning' : 'badge-success') }}">{{ $item->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="3" class="empty">Belum ada laporan. Klik <strong>Kirim Laporan</strong> untuk mulai mengisi kegiatan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

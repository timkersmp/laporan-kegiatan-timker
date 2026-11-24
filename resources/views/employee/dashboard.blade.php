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

<section class="card panel welcome-card">
    <div class="card-body">
        <h2>Selamat datang, {{ $petugas->nama }}! 🎉</h2>
        <p>Kirim laporan kegiatan Anda dan pantau status verifikasinya. Semua riwayat tersimpan rapi di ruang kerja ini.</p>
        <a class="button button-sm" href="{{ route('employee.report') }}"><i class="bx bx-send"></i> Buat laporan baru</a>
        <i class="bx bx-bar-chart-alt-2 welcome-art" aria-hidden="true"></i>
    </div>
</section>

<section class="stats-grid employee-cards">
    <article class="stat">
        <div class="stat-top">
            <span class="stat-icon info"><i class="bx bx-calendar-check"></i></span>
            <a class="stat-more" href="{{ route('employee.completed') }}" aria-label="Lihat kegiatan selesai"><i class="bx bx-dots-vertical-rounded"></i></a>
        </div>
        <span class="stat-label">Kegiatan Dilaporkan</span>
        <strong class="stat-value">{{ $totalKegiatan }}</strong>
        <span class="stat-hint"><i class="bx bx-calendar"></i> Kegiatan yang Anda laporkan</span>
    </article>
    <article class="stat">
        <div class="stat-top">
            <span class="stat-icon success"><i class="bx bx-file-blank"></i></span>
            <a class="stat-more" href="{{ route('employee.completed') }}" aria-label="Lihat riwayat laporan"><i class="bx bx-dots-vertical-rounded"></i></a>
        </div>
        <span class="stat-label">Laporan Terkirim</span>
        <strong class="stat-value">{{ $totalLaporan }}</strong>
        <span class="stat-hint trend-up"><i class="bx bx-up-arrow-alt"></i> Total laporan Anda</span>
    </article>
    <article class="stat">
        <div class="stat-top">
            <span class="stat-icon warning"><i class="bx bx-time-five"></i></span>
            <a class="stat-more" href="{{ route('employee.completed') }}" aria-label="Lihat status laporan"><i class="bx bx-dots-vertical-rounded"></i></a>
        </div>
        <span class="stat-label">Menunggu Verifikasi</span>
        <strong class="stat-value">{{ $menungguVerifikasi }}</strong>
        <span class="stat-hint trend-wait"><i class="bx bx-time"></i> Sedang diperiksa admin</span>
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

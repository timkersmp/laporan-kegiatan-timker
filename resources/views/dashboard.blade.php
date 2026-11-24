@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Ringkasan</span>
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, {{ session('name') }}. Berikut ringkasan aktivitas pengumpulan laporan.</p>
    </div>
    <div class="page-actions"><a class="button primary" href="{{ route('reports.index') }}"><i class="bx bx-inbox"></i> Periksa Laporan</a></div>
</div>

<section class="card panel welcome-card">
    <div class="card-body">
        <h2>Selamat datang, {{ session('name') }}! 🎉</h2>
        <p>Pantau ringkasan kegiatan dan laporan masuk, lalu kelola proses verifikasi dari satu ruang kerja.</p>
        <a class="button button-sm" href="{{ route('reports.index') }}"><i class="bx bx-show"></i> Lihat laporan masuk</a>
        <i class="bx bx-trophy welcome-art" aria-hidden="true"></i>
    </div>
</section>

<section class="stats-grid">
    <article class="stat">
        <div class="stat-top">
            <span class="stat-icon"><i class="bx bx-group"></i></span>
            <a class="stat-more" href="{{ route('staff.index') }}" aria-label="Lihat data karyawan"><i class="bx bx-dots-vertical-rounded"></i></a>
        </div>
        <span class="stat-label">Total Karyawan</span>
        <strong class="stat-value">{{ $totalPetugas }}</strong>
        <span class="stat-hint trend-up"><i class="bx bx-up-arrow-alt"></i> Data karyawan terdaftar</span>
    </article>
    <article class="stat">
        <div class="stat-top">
            <span class="stat-icon info"><i class="bx bx-calendar-event"></i></span>
            <a class="stat-more" href="{{ route('reports.archive') }}" aria-label="Lihat data laporan"><i class="bx bx-dots-vertical-rounded"></i></a>
        </div>
        <span class="stat-label">Total Kegiatan</span>
        <strong class="stat-value">{{ $totalKegiatan }}</strong>
        <span class="stat-hint"><i class="bx bx-bar-chart-alt-2"></i> Kegiatan tercatat</span>
    </article>
    <article class="stat">
        <div class="stat-top">
            <span class="stat-icon success"><i class="bx bx-file-blank"></i></span>
            <a class="stat-more" href="{{ route('reports.archive') }}" aria-label="Lihat arsip laporan"><i class="bx bx-dots-vertical-rounded"></i></a>
        </div>
        <span class="stat-label">Total Laporan</span>
        <strong class="stat-value">{{ $totalLaporan }}</strong>
        <span class="stat-hint trend-up"><i class="bx bx-up-arrow-alt"></i> Seluruh laporan masuk</span>
    </article>
    <article class="stat">
        <div class="stat-top">
            <span class="stat-icon warning"><i class="bx bx-time-five"></i></span>
            <a class="stat-more" href="{{ route('reports.index') }}" aria-label="Periksa laporan"><i class="bx bx-dots-vertical-rounded"></i></a>
        </div>
        <span class="stat-label">Menunggu Verifikasi</span>
        <strong class="stat-value">{{ $totalMenunggu }}</strong>
        <span class="stat-hint trend-wait"><i class="bx bx-time"></i> Perlu ditindaklanjuti</span>
    </article>
</section>

<section class="card panel">
    <div class="card-header"><h2>Menu pengelolaan</h2><span class="muted" style="font-size:12px">Akses cepat</span></div>
    <div class="card-body">
        <nav class="menu-links">
            <a href="{{ route('staff.index') }}"><i class="bx bx-group"></i>Data Karyawan<span>Kelola akun dan biodata karyawan</span></a>
            <a href="{{ route('reports.index') }}"><i class="bx bx-inbox"></i>Penerimaan Laporan<span>Periksa dan verifikasi laporan masuk</span></a>
            <a href="{{ route('reports.archive') }}"><i class="bx bx-archive"></i>Data Laporan<span>Lihat arsip seluruh laporan</span></a>
            <a href="{{ route('accounts.index') }}"><i class="bx bx-user-check"></i>Kelola Akun<span>Atur akun admin dan karyawan</span></a>
        </nav>
    </div>
</section>
@endsection

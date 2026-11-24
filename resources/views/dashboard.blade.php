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

<section class="stats-grid">
    <article class="stat">
        <span class="stat-icon"><i class="bx bx-group"></i></span>
        <div><span class="stat-label">Total Karyawan</span><strong class="stat-value">{{ $totalPetugas }}</strong><span class="stat-hint">Data karyawan terdaftar</span></div>
    </article>
    <article class="stat">
        <span class="stat-icon info"><i class="bx bx-calendar-event"></i></span>
        <div><span class="stat-label">Total Kegiatan</span><strong class="stat-value">{{ $totalKegiatan }}</strong><span class="stat-hint">Kegiatan tercatat</span></div>
    </article>
    <article class="stat">
        <span class="stat-icon success"><i class="bx bx-file-blank"></i></span>
        <div><span class="stat-label">Total Laporan</span><strong class="stat-value">{{ $totalLaporan }}</strong><span class="stat-hint">Seluruh laporan masuk</span></div>
    </article>
    <article class="stat">
        <span class="stat-icon warning"><i class="bx bx-time-five"></i></span>
        <div><span class="stat-label">Menunggu Verifikasi</span><strong class="stat-value">{{ $totalMenunggu }}</strong><span class="stat-hint">Perlu ditindaklanjuti</span></div>
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

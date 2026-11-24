@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-heading"><div><p class="eyebrow">RUANG KERJA</p><h1>Dashboard Admin</h1><p>Selamat datang, {{ session('name') }}.</p></div></div>
<section class="cards"><article class="stat"><span>Total Karyawan</span><strong>{{ $totalPetugas }}</strong></article><article class="stat"><span>Total Kegiatan</span><strong>{{ $totalKegiatan }}</strong></article><article class="stat"><span>Total Laporan</span><strong>{{ $totalLaporan }}</strong></article><article class="stat"><span>Menunggu Verifikasi</span><strong>{{ $totalMenunggu }}</strong></article></section>
<section class="panel"><h2>Menu pengelolaan</h2><nav class="menu-links"><a href="{{ route('staff.index') }}">Data Karyawan <span>Kelola akun dan biodata karyawan</span></a><a href="{{ route('reports.index') }}">Penerimaan Laporan <span>Periksa dan verifikasi laporan masuk</span></a><a href="{{ route('reports.archive') }}">Data Laporan <span>Lihat arsip semua laporan</span></a><a href="{{ route('accounts.index') }}">Kelola Akun <span>Atur akun admin dan karyawan</span></a></nav></section>
@endsection

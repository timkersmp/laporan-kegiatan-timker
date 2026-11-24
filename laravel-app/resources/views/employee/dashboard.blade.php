@extends('layouts.app')
@section('title', 'Dashboard Karyawan')
@section('content')
<div class="page-heading"><div><p class="eyebrow">RUANG KARYAWAN</p><h1>Dashboard</h1><p>Selamat datang, {{ $petugas->nama }}. Pantau laporan yang Anda kirim di sini.</p></div></div>
<section class="cards employee-cards"><article class="stat"><span>Kegiatan Dilaporkan</span><strong>{{ $totalKegiatan }}</strong></article><article class="stat"><span>Laporan Terkirim</span><strong>{{ $totalLaporan }}</strong></article><article class="stat"><span>Menunggu Verifikasi</span><strong>{{ $menungguVerifikasi }}</strong></article></section>
<section class="panel"><div class="section-heading"><h2>Laporan Terbaru</h2><a class="button quiet" href="{{ route('employee.completed') }}">Lihat semua kegiatan selesai</a></div><div class="table-wrap"><table><thead><tr><th>Kegiatan</th><th>Tanggal Kirim</th><th>Status</th></tr></thead><tbody>
@forelse($laporanTerbaru as $item)<tr><td>{{ $item->kegiatan?->nama_kegiatan ?: 'Kegiatan dihapus' }}</td><td>{{ $item->dibuat_pada?->format('d M Y H:i') ?: '—' }}</td><td><span class="badge">{{ $item->status }}</span></td></tr>@empty<tr><td colspan="3" class="empty">Belum ada laporan. Isi data kegiatan untuk mulai mengirim laporan.</td></tr>@endforelse
</tbody></table></div><a class="button primary employee-cta" href="{{ route('employee.report') }}">Kirim Laporan</a></section>
@endsection

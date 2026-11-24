@extends('layouts.app')
@section('title', 'Penerimaan Laporan')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Penerimaan</span>
        <h1>Penerimaan Laporan</h1>
        <p>Periksa laporan yang masuk dan tentukan status verifikasinya.</p>
    </div>
    <div class="page-actions">
        @if(session('role') === 'admin_biasa')<a class="button quiet" href="{{ route('dashboard') }}"><i class="bx bx-arrow-back"></i> Dashboard</a>@endif
        <a class="button primary" href="{{ route('reports.archive') }}"><i class="bx bx-archive"></i> Data Laporan</a>
    </div>
</div>

@forelse($laporan as $item)
<article class="card panel report">
    <div class="card-header">
        <h2>{{ $item->kegiatan?->nama_kegiatan ?: 'Kegiatan dihapus' }}</h2>
        <span class="badge {{ in_array($item->status, ['Ditolak']) ? 'badge-danger' : (in_array($item->status, ['Perlu Diperbaiki', 'perlu_diperbaiki']) ? 'badge-warning' : 'badge-success') }}">{{ $item->status }}</span>
    </div>
    <div class="card-body">
        <div class="report-top">
            <p><i class="bx bx-user" style="color:var(--primary)"></i> {{ $item->petugas?->nama ?: 'Petugas dihapus' }} · {{ $item->petugas?->nip ?: 'NIP —' }} &nbsp; <i class="bx bx-time-five" style="color:var(--muted)"></i> {{ $item->dibuat_pada?->format('d M Y H:i') }}</p>
        </div>
        <div class="report-body">{!! nl2br(e($item->isi_laporan)) !!}</div>
        @if($item->lampiran)
            <p><a href="{{ route('reports.attachment', $item) }}" target="_blank" rel="noopener"><i class="bx bx-paperclip"></i> Lihat lampiran</a></p>
        @endif
        <form class="review-form" method="post" action="{{ route('reports.review', $item) }}">
            @csrf @method('patch')
            <label>Status
                <select name="status">
                    <option value="Lengkap" @selected(in_array($item->status, ['Lengkap','disetujui']))>Lengkap / Disetujui</option>
                    <option value="Perlu Diperbaiki" @selected(in_array($item->status, ['Perlu Diperbaiki','perlu_diperbaiki']))>Perlu diperbaiki</option>
                </select>
            </label>
            <label>Catatan <small>Wajib jika laporan perlu diperbaiki atau ditolak</small>
                <textarea name="catatan_admin" rows="2">{{ $item->catatan_admin }}</textarea>
            </label>
            <div class="review-actions">
                <button class="button primary"><i class="bx bx-check"></i> Simpan verifikasi</button>
                <button class="button danger" type="submit" formaction="{{ route('reports.reject', $item) }}"><i class="bx bx-x"></i> Tolak Laporan</button>
            </div>
        </form>
    </div>
</article>
@empty
<section class="card panel empty"><i class="bx bx-inbox" style="font-size:34px;display:block;margin-bottom:8px;color:var(--muted)"></i>Belum ada laporan yang menunggu verifikasi.</section>
@endforelse
@endsection

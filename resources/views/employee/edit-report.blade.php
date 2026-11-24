@extends('layouts.app')
@section('title', 'Ubah Laporan')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Riwayat Karyawan</span>
        <h1>Ubah Laporan</h1>
        <p>Perbarui informasi kegiatan dan laporan Anda. Setelah disimpan, laporan akan dikirim kembali untuk verifikasi.</p>
    </div>
    <div class="page-actions"><a class="button quiet" href="{{ route('employee.completed') }}"><i class="bx bx-arrow-back"></i> Kembali</a></div>
</div>

<section class="card panel report-form-panel">
    <div class="card-header"><h2>Detail laporan</h2><i class="bx bx-edit" style="color:var(--primary);font-size:20px"></i></div>
    <div class="card-body">
        <form class="form-grid" method="post" action="{{ route('employee.reports.update', $laporan) }}" enctype="multipart/form-data">
            @csrf @method('patch')
            <label>Nama Kegiatan<input name="nama_kegiatan" value="{{ old('nama_kegiatan', $laporan->kegiatan?->nama_kegiatan) }}" required maxlength="255"></label>
            <label>Tanggal Kegiatan<input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $laporan->kegiatan?->tanggal_kegiatan?->format('Y-m-d')) }}" required></label>
            <label>Lokasi Kegiatan<input name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan', $laporan->kegiatan?->lokasi_kegiatan) }}" required maxlength="255"></label>
            <label class="span-two">Isi Laporan<textarea name="isi_laporan" rows="7" required minlength="10">{{ old('isi_laporan', $laporan->isi_laporan) }}</textarea></label>
            <div class="span-two attachment-editor">
                <strong>Lampiran</strong>
                @if($laporan->lampiran)
                    <p><a href="{{ route('reports.attachment', $laporan) }}" target="_blank" rel="noopener"><i class="bx bx-paperclip"></i> Lihat lampiran saat ini</a></p>
                    <label class="check-card"><input type="checkbox" name="hapus_lampiran" value="1"><span>Hapus lampiran saat ini</span></label>
                @else
                    <p class="muted">Belum ada lampiran.</p>
                @endif
                <label class="file-caption" for="edit-own-attachment">Ganti / tambah lampiran <small>Opsional · PDF, gambar, atau dokumen (maks. 10 MB)</small></label>
                <div class="file-picker">
                    <input class="file-input" id="edit-own-attachment" type="file" name="lampiran_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <label class="button quiet" for="edit-own-attachment"><i class="bx bx-upload"></i> Pilih berkas</label>
                    <span class="file-name" id="edit-own-attachment-name">Belum ada berkas dipilih</span>
                </div>
            </div>
            <div class="form-action span-two">
                <button class="button primary"><i class="bx bx-send"></i> Simpan dan Kirim Ulang</button>
                <a class="button quiet" href="{{ route('employee.completed') }}">Batal</a>
            </div>
        </form>
    </div>
</section>
<script>document.getElementById('edit-own-attachment')?.addEventListener('change', function () { document.getElementById('edit-own-attachment-name').textContent = this.files[0]?.name || 'Belum ada berkas dipilih'; });</script>
@endsection

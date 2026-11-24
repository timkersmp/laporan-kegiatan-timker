@extends('layouts.app')
@section('title', 'Kirim Laporan')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Pelaporan Karyawan</span>
        <h1>Kirim Laporan</h1>
        <p>Isi data kegiatan dan laporan untuk dikirim ke admin.</p>
    </div>
    <div class="page-actions"><a class="button quiet" href="{{ route('employee.completed') }}"><i class="bx bx-arrow-back"></i> Riwayat kegiatan</a></div>
</div>

<section class="card panel report-form-panel">
    <div class="card-header"><h2>Formulir laporan</h2><i class="bx bx-send" style="color:var(--primary);font-size:20px"></i></div>
    <div class="card-body">
        <form class="report-submit" id="employee-report-form" method="post" action="{{ route('reports.store') }}" enctype="multipart/form-data">
            @csrf
            <label>Nama Kegiatan<input name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required maxlength="255" placeholder="Masukkan nama kegiatan"></label>
            <div class="form-grid report-meta-grid">
                <label>Tanggal Kegiatan<input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}" required></label>
                <label>Lokasi Kegiatan<input name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" required maxlength="255" placeholder="Masukkan lokasi kegiatan"></label>
            </div>
            <label>Isi Laporan<textarea name="isi_laporan" rows="7" required minlength="10" placeholder="Tuliskan hasil kegiatan, jumlah peserta, dan informasi penting lainnya.">{{ old('isi_laporan') }}</textarea></label>
            <div class="file-field">
                <label class="file-caption" for="report-attachment">Lampiran <small>Opsional · PDF, gambar, atau dokumen (maks. 10 MB)</small></label>
                <div class="file-picker">
                    <input class="file-input" id="report-attachment" type="file" name="lampiran_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <label class="button quiet" for="report-attachment"><i class="bx bx-upload"></i> Masukan laporan</label>
                    <span class="file-name" id="report-attachment-name">Belum ada berkas dipilih</span>
                </div>
            </div>
            <button class="button primary" type="submit"><i class="bx bx-send"></i> Kirim Laporan</button>
        </form>
    </div>
</section>
<script>
const reportForm = document.getElementById('employee-report-form');
const reportValidationMessages = {
    nama_kegiatan: 'Nama kegiatannya belum diisi.',
    tanggal_kegiatan: 'Tanggal kegiatannya belum dipilih.',
    lokasi_kegiatan: 'Lokasi kegiatannya belum diisi.',
    isi_laporan: 'Isi laporannya belum diisi.'
};
reportForm?.querySelectorAll('[required]').forEach((field) => {
    field.addEventListener('invalid', () => {
        const message = field.validity.valueMissing
            ? (field.name === 'isi_laporan' ? reportValidationMessages.isi_laporan : reportValidationMessages[field.name])
            : field.validity.tooShort ? 'Isi laporan minimal 10 karakter, ya.' : '';
        field.setCustomValidity(message || 'Mohon periksa kembali isian ini.');
    });
    ['input', 'change'].forEach((eventName) => field.addEventListener(eventName, () => field.setCustomValidity('')));
});
document.getElementById('report-attachment')?.addEventListener('change', function () {
    document.getElementById('report-attachment-name').textContent = this.files[0]?.name || 'Belum ada berkas dipilih';
});
</script>
@endsection

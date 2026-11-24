@extends('layouts.app')
@section('title', 'Data Karyawan')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Manajemen</span>
        <h1>Data Karyawan</h1>
        <p>Kelola data karyawan beserta akun yang digunakan untuk mengirim laporan.</p>
    </div>
    <div class="page-actions"><a class="button quiet" href="{{ route('dashboard') }}"><i class="bx bx-arrow-back"></i> Dashboard</a></div>
</div>

<section class="card panel">
    <div class="card-header"><h2>Tambah data karyawan</h2><i class="bx bx-user-plus" style="color:var(--primary);font-size:20px"></i></div>
    <div class="card-body">
        <form class="form-grid" method="post" action="{{ route('staff.store') }}">
            @csrf
            <label>Nama lengkap<input name="nama" value="{{ old('nama') }}" placeholder="Nama karyawan" required></label>
            <label>NIP<input name="nip" value="{{ old('nip') }}" placeholder="Nomor induk pegawai" required></label>
            <label>Jabatan<input name="jabatan" value="{{ old('jabatan') }}" placeholder="Jabatan" required></label>
            <label>Username<input name="username" value="{{ old('username') }}" autocomplete="off" placeholder="Username login" required></label>
            <label>Password <small>Minimal 6 karakter</small><input type="password" name="password" autocomplete="new-password" placeholder="Password login" required></label>
            <div class="form-action"><button class="button primary"><i class="bx bx-save"></i> Simpan karyawan</button></div>
        </form>
    </div>
</section>

<section class="card panel">
    <div class="card-header"><h2>Daftar karyawan</h2><span class="badge-info badge">{{ $petugas->count() }} orang</span></div>
    <div class="table-toolbar">
        <div class="table-search"><i class="bx bx-search"></i><input type="search" data-table-target="#staffTable" placeholder="Cari nama, NIP, atau username..."></div>
    </div>
    <div class="table-wrap">
        <table id="staffTable">
            <thead><tr><th>Nama</th><th>NIP</th><th>Jabatan</th><th>Username</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($petugas as $item)
                <tr>
                    <td><strong>{{ $item->nama }}</strong></td>
                    <td>{{ $item->nip ?: '—' }}</td>
                    <td>{{ $item->jabatan ?: '—' }}</td>
                    <td>{{ $item->username ?: 'Belum ada' }}</td>
                    <td>
                        <form method="post" action="{{ route('staff.destroy', $item) }}" onsubmit="return confirm('Hapus data karyawan ini?')">
                            @csrf @method('delete')
                            <button class="button danger button-sm"><i class="bx bx-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada karyawan. Tambahkan data pertama melalui form di atas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

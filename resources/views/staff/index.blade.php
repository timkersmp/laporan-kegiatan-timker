@extends('layouts.app')
@section('title', 'Data Karyawan')
@section('content')
<div class="page-heading"><div><p class="eyebrow">ADMINISTRASI</p><h1>Data Karyawan</h1></div><a class="button quiet" href="{{ route('dashboard') }}">Dashboard</a></div>
<section class="panel"><h2>Tambah data karyawan</h2><form class="form-grid" method="post" action="{{ route('staff.store') }}">@csrf
<label>Nama lengkap<input name="nama" value="{{ old('nama') }}" required></label>
<label>NIP<input name="nip" value="{{ old('nip') }}" required></label>
<label>Jabatan<input name="jabatan" value="{{ old('jabatan') }}" required></label>
<label>Username<input name="username" value="{{ old('username') }}" autocomplete="off" required></label>
<label>Password <small>Minimal 6 karakter</small><input type="password" name="password" autocomplete="new-password" required></label>
<div class="form-action"><button class="button primary">Simpan karyawan</button></div></form></section>
<section class="panel"><h2>Daftar karyawan</h2><div class="table-wrap"><table><thead><tr><th>Nama</th><th>NIP</th><th>Jabatan</th><th>Username</th><th></th></tr></thead><tbody>@forelse($petugas as $item)<tr><td>{{ $item->nama }}</td><td>{{ $item->nip ?: '—' }}</td><td>{{ $item->jabatan ?: '—' }}</td><td>{{ $item->username ?: 'Belum ada' }}</td><td><form method="post" action="{{ route('staff.destroy', $item) }}" onsubmit="return confirm('Hapus data karyawan ini?')">@csrf @method('delete')<button class="button danger">Hapus</button></form></td></tr>@empty<tr><td colspan="5" class="empty">Belum ada karyawan.</td></tr>@endforelse</tbody></table></div></section>
@endsection

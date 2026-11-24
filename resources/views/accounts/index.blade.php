@extends('layouts.app')
@section('title', 'Kelola Akun Admin')
@section('content')
<div class="page-heading"><div><p class="eyebrow">ADMINISTRASI</p><h1>Kelola Akun</h1><p>Buat, ubah, nonaktifkan, atau hapus akun admin dan karyawan.</p></div></div>

<section class="panel" id="form-akun">
    <h2>{{ $editAccount ? 'Ubah akun' : 'Buat akun baru' }}</h2>
    <form class="form-grid" method="post" action="{{ $editAccount ? route('accounts.update', $editAccount) : route('accounts.store') }}">
        @csrf @if($editAccount) @method('patch') @endif
        <label>Nama lengkap<input name="nama" value="{{ old('nama', $editAccount?->nama) }}" required></label>
        <label>NIP <small>Opsional</small><input name="nip" value="{{ old('nip', $editAccount?->nip) }}"></label>
        <label>Username<input name="username" value="{{ old('username', $editAccount?->username) }}" required autocomplete="off"></label>
        <label>Password <small>{{ $editAccount ? 'Kosongkan bila tidak ingin mengganti' : 'Minimal 6 karakter' }}</small><input type="password" name="password" {{ $editAccount ? '' : 'required' }} autocomplete="new-password"></label>
        <label>Peran<select name="role" required><option value="admin_biasa" @selected(old('role', $editAccount?->role) === 'admin_biasa')>Admin</option><option value="verifikator" @selected(old('role', $editAccount?->role) === 'verifikator')>Karyawan</option></select></label>
        <div class="form-action"><button class="button primary">{{ $editAccount ? 'Simpan perubahan' : 'Buat akun' }}</button> @if($editAccount)<a class="button quiet" href="{{ route('accounts.index') }}">Batal</a>@endif</div>
    </form>
</section>

<section class="panel"><h2>Daftar akun</h2><div class="table-wrap"><table><thead><tr><th>Nama</th><th>Username</th><th>NIP</th><th>Peran</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
@forelse($accounts as $account)
<tr><td>{{ $account->nama }}</td><td>{{ $account->username }}</td><td>{{ $account->nip ?: '—' }}</td><td>{{ $account->role === 'verifikator' ? 'Karyawan' : 'Admin' }}</td><td><span class="badge {{ ($account->status ?? 'aktif') === 'aktif' ? 'status-active' : 'status-inactive' }}">{{ ucfirst($account->status ?? 'aktif') }}</span></td><td><div class="account-actions"><a class="button quiet" href="{{ route('accounts.index', ['edit' => $account->id]) }}#form-akun">Ubah</a>
<form method="post" action="{{ route('accounts.status', $account) }}">@csrf @method('patch')<input type="hidden" name="status" value="{{ ($account->status ?? 'aktif') === 'aktif' ? 'nonaktif' : 'aktif' }}"><button class="button {{ ($account->status ?? 'aktif') === 'aktif' ? 'warning' : 'primary' }}">{{ ($account->status ?? 'aktif') === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
<form method="post" action="{{ route('accounts.destroy', $account) }}" onsubmit="return confirm('Hapus akun ini?')">@csrf @method('delete')<button class="button danger">Hapus</button></form></div></td></tr>
@empty<tr><td colspan="6" class="empty">Belum ada akun admin atau karyawan.</td></tr>@endforelse
</tbody></table></div></section>
@endsection

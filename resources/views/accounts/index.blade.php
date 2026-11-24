@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Administrasi</span>
        <h1>Kelola Akun</h1>
        <p>Buat, ubah, nonaktifkan, atau hapus akun admin dan karyawan.</p>
    </div>
    <div class="page-actions"><a class="button quiet" href="{{ route('dashboard') }}"><i class="bx bx-arrow-back"></i> Dashboard</a></div>
</div>

<section class="card panel" id="form-akun">
    <div class="card-header">
        <h2>{{ $editAccount ? 'Ubah akun' : 'Buat akun baru' }}</h2>
        <i class="bx {{ $editAccount ? 'bx-edit' : 'bx-user-plus' }}" style="color:var(--primary);font-size:20px"></i>
    </div>
    <div class="card-body">
        <form class="form-grid" method="post" action="{{ $editAccount ? route('accounts.update', $editAccount) : route('accounts.store') }}">
            @csrf @if($editAccount) @method('patch') @endif
            <label>Nama lengkap<input name="nama" value="{{ old('nama', $editAccount?->nama) }}" required></label>
            <label>NIP <small>Opsional</small><input name="nip" value="{{ old('nip', $editAccount?->nip) }}"></label>
            <label>Username<input name="username" value="{{ old('username', $editAccount?->username) }}" required autocomplete="off"></label>
            <label>Password <small>{{ $editAccount ? 'Kosongkan bila tidak ingin mengganti' : 'Minimal 6 karakter' }}</small><input type="password" name="password" {{ $editAccount ? '' : 'required' }} autocomplete="new-password"></label>
            <label>Peran
                <select name="role" required>
                    <option value="admin_biasa" @selected(old('role', $editAccount?->role) === 'admin_biasa')>Admin</option>
                    <option value="verifikator" @selected(old('role', $editAccount?->role) === 'verifikator')>Karyawan</option>
                </select>
            </label>
            <div class="form-action">
                <button class="button primary"><i class="bx bx-save"></i> {{ $editAccount ? 'Simpan perubahan' : 'Buat akun' }}</button>
                @if($editAccount)<a class="button quiet" href="{{ route('accounts.index') }}">Batal</a>@endif
            </div>
        </form>
    </div>
</section>

<section class="card panel">
    <div class="card-header"><h2>Daftar akun</h2><span class="badge-info badge">{{ $accounts->count() }} akun</span></div>
    <div class="table-toolbar">
        <div class="table-search"><i class="bx bx-search"></i><input type="search" data-table-target="#accountsTable" placeholder="Cari nama, username, atau peran..."></div>
    </div>
    <div class="table-wrap">
        <table id="accountsTable">
            <thead><tr><th>Nama</th><th>Username</th><th>NIP</th><th>Peran</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td><strong>{{ $account->nama }}</strong></td>
                    <td>{{ $account->username }}</td>
                    <td>{{ $account->nip ?: '—' }}</td>
                    <td><span class="badge badge-info">{{ $account->role === 'verifikator' ? 'Karyawan' : 'Admin' }}</span></td>
                    <td><span class="badge {{ ($account->status ?? 'aktif') === 'aktif' ? 'status-active' : 'status-inactive' }}">{{ ucfirst($account->status ?? 'aktif') }}</span></td>
                    <td>
                        <div class="account-actions">
                            <a class="button quiet button-sm" href="{{ route('accounts.index', ['edit' => $account->id]) }}#form-akun"><i class="bx bx-edit"></i> Ubah</a>
                            <form method="post" action="{{ route('accounts.status', $account) }}">
                                @csrf @method('patch')
                                <input type="hidden" name="status" value="{{ ($account->status ?? 'aktif') === 'aktif' ? 'nonaktif' : 'aktif' }}">
                                <button class="button {{ ($account->status ?? 'aktif') === 'aktif' ? 'warning' : 'primary' }} button-sm">{{ ($account->status ?? 'aktif') === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                            <form method="post" action="{{ route('accounts.destroy', $account) }}" onsubmit="return confirm('Hapus akun ini?')">
                                @csrf @method('delete')
                                <button class="button danger button-sm"><i class="bx bx-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Belum ada akun admin atau karyawan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

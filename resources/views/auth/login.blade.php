<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#696cff">
    <title>Masuk • BBPMP Jawa Barat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
<main class="login-layout">
    <section class="login-intro">
        <img src="{{ asset('images/logo-tut-wuri.png') }}" alt="Logo Tut Wuri Handayani">
        <h1>BBPMP<br>Provinsi Jawa Barat</h1>
        <p>Sistem Pengumpulan &amp; Verifikasi Laporan Kegiatan</p>
        <small>Bersama Meningkatkan Mutu Pendidikan</small>
    </section>

    <section class="login-card">
        <h2>Selamat datang!</h2>
        <p>Masuk untuk mengakses ruang kerja sesuai akun Anda.</p>

        @if(session('error'))
            <div class="notice error" role="alert"><i class="bx bx-error-circle"></i><span>{{ session('error') }}</span></div>
        @endif
        @if($errors->any())
            <div class="notice error" role="alert"><i class="bx bx-error-circle"></i><span>{{ $errors->first() }}</span></div>
        @endif

        <form method="post" action="{{ route('login.store') }}">
            @csrf
            <label>Peran
                <select name="role" required>
                    <option value="">Pilih peran</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="karyawan" @selected(old('role') === 'karyawan')>Karyawan</option>
                </select>
            </label>
            <label>Username
                <input name="username" value="{{ old('username') }}" autocomplete="username" placeholder="Masukkan username" required autofocus>
            </label>
            <label>Password
                <div class="password-field">
                    <input id="login-password" type="password" name="password" autocomplete="current-password" placeholder="Masukkan password" required>
                    <button class="password-toggle" type="button" aria-controls="login-password" aria-label="Tampilkan kata sandi" aria-pressed="false">Tampilkan</button>
                </div>
            </label>
            <button class="button primary full" type="submit"><i class="bx bx-log-in"></i> Masuk</button>
        </form>
    </section>
</main>

<script>
const roleSelect = document.querySelector('select[name="role"]');
roleSelect?.addEventListener('invalid', () => roleSelect.setCustomValidity('Anda belum memilih peran.'));
roleSelect?.addEventListener('change', () => roleSelect.setCustomValidity(''));
document.querySelector('.password-toggle')?.addEventListener('click', function () {
    const password = document.getElementById(this.getAttribute('aria-controls'));
    const show = password.type === 'password';
    password.type = show ? 'text' : 'password';
    this.textContent = show ? 'Sembunyikan' : 'Tampilkan';
    this.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    this.setAttribute('aria-pressed', String(show));
});
</script>
</body>
</html>

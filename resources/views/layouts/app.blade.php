<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#696cff">
    <title>@yield('title', 'Pengumpulan Laporan BBPMP')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=2">
</head>
<body>
@php
    $role = session('role');
    $navGroups = $role === 'petugas'
        ? [
            ['label' => 'Ruang Karyawan', 'items' => [
                ['employee.dashboard', 'Dashboard', 'bx bx-home-alt-2'],
                ['employee.completed', 'Kegiatan Selesai', 'bx bx-check-circle'],
                ['employee.report', 'Kirim Laporan', 'bx bx-send'],
            ]],
        ]
        : ($role === 'verifikator'
            ? [
                ['label' => 'Manajemen Laporan', 'items' => [
                    ['reports.index', 'Penerimaan Laporan', 'bx bx-inbox'],
                    ['reports.archive', 'Data Laporan', 'bx bx-archive'],
                ]],
            ]
            : [
                ['label' => 'Utama', 'items' => [
                    ['dashboard', 'Dashboard', 'bx bx-home-alt-2'],
                ]],
                ['label' => 'Manajemen', 'items' => [
                    ['staff.index', 'Data Karyawan', 'bx bx-group'],
                    ['accounts.index', 'Kelola Akun', 'bx bx-user-check'],
                ]],
                ['label' => 'Laporan', 'items' => [
                    ['reports.index', 'Penerimaan Laporan', 'bx bx-inbox'],
                    ['reports.archive', 'Data Laporan', 'bx bx-archive'],
                ]],
            ]);
    $homeRoute = $role === 'petugas' ? 'employee.dashboard' : ($role === 'verifikator' ? 'reports.index' : 'dashboard');
    $roleLabel = match ($role) { 'petugas' => 'Karyawan', 'verifikator' => 'Verifikator', default => 'Administrator' };
    $initial = strtoupper(substr(session('name', '?'), 0, 1));
@endphp

<div class="layout-wrapper">
    <div class="menu-overlay" id="menuOverlay"></div>

    <aside class="layout-menu" id="layoutMenu">
        <a class="app-brand" href="{{ route($homeRoute) }}">
            <img src="{{ asset('images/logo-tut-wuri.png') }}" alt="Logo Tut Wuri Handayani">
            <span><strong>BBPMP</strong><small>Provinsi Jawa Barat</small></span>
        </a>

        <nav class="menu-container">
            @foreach($navGroups as $group)
                <div class="menu-header">{{ $group['label'] }}</div>
                @foreach($group['items'] as [$routeName, $label, $icon])
                    <a class="menu-link {{ request()->routeIs($routeName) ? 'active' : '' }}"
                       href="{{ route($routeName) }}"
                       @if(request()->routeIs($routeName)) aria-current="page" @endif>
                        <i class="{{ $icon }}"></i><span>{{ $label }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="menu-footer">
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="menu-link" style="width:100%;border:0;background:transparent;font:inherit;cursor:pointer;text-align:left">
                    <i class="bx bx-log-out"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="layout-main">
        <header class="layout-navbar">
            <button class="navbar-toggler" id="navbarToggler" type="button" aria-label="Buka menu" aria-expanded="false">
                <i class="bx bx-menu"></i>
            </button>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route($homeRoute) }}">Beranda</a>
                <i class="bx bx-chevron-right"></i>
                <span>{{ View::yieldContent('title', 'Dashboard') }}</span>
            </nav>
            <div class="navbar-spacer"></div>
            <div class="user-menu">
                <span class="avatar">{{ $initial }}</span>
                <span class="user-meta"><strong>{{ session('name') }}</strong><small>{{ $roleLabel }}</small></span>
                <i class="bx bx-chevron-down" style="color:var(--muted)"></i>
            </div>
        </header>

        <main class="container">
            @if(session('success'))
                <div class="notice success" role="status"><i class="bx bx-check-circle"></i><span>{{ session('success') }}</span></div>
            @endif
            @if(session('error'))
                <div class="notice error" role="alert"><i class="bx bx-error-circle"></i><span>{{ session('error') }}</span></div>
            @endif
            @if($errors->any())
                <div class="notice error" role="alert">
                    <i class="bx bx-error-circle"></i>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>

        <footer class="app-footer">© {{ date('Y') }} Pengumpulan Laporan BBPMP · Provinsi Jawa Barat</footer>
    </div>
</div>

<script>
(function () {
    const toggler = document.getElementById('navbarToggler');
    const overlay = document.getElementById('menuOverlay');
    const closeMenu = () => {
        document.body.classList.remove('menu-open');
        toggler?.setAttribute('aria-expanded', 'false');
    };
    toggler?.addEventListener('click', () => {
        const open = document.body.classList.toggle('menu-open');
        toggler.setAttribute('aria-expanded', String(open));
    });
    overlay?.addEventListener('click', closeMenu);
    document.querySelectorAll('#layoutMenu .menu-link').forEach((link) => link.addEventListener('click', closeMenu));

    document.querySelectorAll('.table-search input[data-table-target]').forEach((input) => {
        input.addEventListener('input', () => {
            const table = document.querySelector(input.dataset.tableTarget);
            if (!table) return;
            const query = input.value.trim().toLowerCase();
            table.querySelectorAll('tbody tr').forEach((row) => {
                if (row.querySelector('td.empty')) return;
                row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
            });
        });
    });
})();
</script>
</body>
</html>

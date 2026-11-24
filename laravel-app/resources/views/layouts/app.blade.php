<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pengumpulan Laporan BBPMP')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="app-shell">
@php
    $role = session('role');
    $nav = $role === 'petugas'
        ? [['employee.dashboard','Dashboard','⌂'], ['employee.completed','Kegiatan Selesai','✓'], ['employee.report','Kirim Laporan','↗']]
        : ($role === 'verifikator'
            ? [['reports.index','Penerimaan Laporan','✓'], ['reports.archive','Data Laporan','▤']]
            : [['dashboard','Dashboard','⌂'], ['accounts.index','Kelola Akun','⚙'], ['staff.index','Karyawan','♙'], ['reports.index','Penerimaan Laporan','↓'], ['reports.archive','Data Laporan','▤']]);
    $homeRoute = $role === 'petugas' ? 'employee.dashboard' : ($role === 'verifikator' ? 'reports.index' : 'dashboard');
@endphp
<aside class="sidebar">
    <a class="brand" href="{{ route($homeRoute) }}"><img src="{{ asset('images/logo-tut-wuri.png') }}" alt="Logo Tut Wuri Handayani"><span><strong>BBPMP</strong><small>Provinsi Jawa Barat</small></span></a>
    <nav class="side-nav">@foreach($nav as [$routeName,$label,$icon])<a class="{{ request()->routeIs($routeName) ? 'active' : '' }}" href="{{ route($routeName) }}"><span class="nav-icon">{{ $icon }}</span>{{ $label }}</a>@endforeach</nav>
    <form class="side-logout" method="post" action="{{ route('logout') }}">@csrf<button type="submit"><span class="nav-icon">↪</span>Keluar</button></form>
</aside>
<div class="main-area">
    <header class="topbar"><div class="mobile-brand">BBPMP <span>Jawa Barat</span></div><div class="userbox"><span class="user-avatar">♙</span><span>{{ session('name') }}</span></div></header>
    <main class="container">
        @if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="notice error">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="notice error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </main>
    <footer>© {{ date('Y') }} Pengumpulan Laporan BBPMP · Jawa Barat</footer>
</div>
</body></html>

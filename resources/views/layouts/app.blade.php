<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', config('app.name', 'La Bakery')) - {{ config('app.name', 'La Bakery') }}</title>

    {{-- Vendor & Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sellora-theme-override.css') }}?v=sellora-blue-1">
    <link rel="stylesheet" href="{{ asset('css/ui-cleanup.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/ui-pages.css') }}?v=1">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
</head>

{{-- Global confirm modal (tanpa jQuery/Bootstrap JS) --}}
<div id="app-confirm" class="cfm" aria-hidden="true">
    <div class="cfm__box">
        <h5 class="mb-2">Konfirmasi</h5>
        <p id="app-confirm-text" class="mb-3">Apakah Anda yakin?</p>
        <div class="text-right">
            <button type="button" class="btn btn-light mr-2" data-cfm="cancel">Batal</button>
            <button type="button" class="btn btn-danger" data-cfm="ok">Hapus</button>
        </div>
    </div>
</div>

<style>
    .cfm {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, .35);
        z-index: 2000
    }

    .cfm.show {
        display: flex
    }

    .cfm__box {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .2)
    }
</style>

<script>
    (() => {
        let targetEl = null;
        const root = document.getElementById('app-confirm');
        const text = document.getElementById('app-confirm-text');

        // Tangkap semua klik pada elemen yang punya data-confirm
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-confirm]');
            if (!btn) return;

            // STOP submit/link default
            e.preventDefault();

            targetEl = btn;
            text.textContent = btn.getAttribute('data-confirm') || 'Apakah Anda yakin?';
            root.classList.add('show');
            root.setAttribute('aria-hidden', 'false');
        });

        // Klik "Batal", backdrop, atau "OK"
        root.addEventListener('click', (e) => {
            if (e.target === root || e.target.dataset.cfm === 'cancel') {
                root.classList.remove('show');
                root.setAttribute('aria-hidden', 'true');
                targetEl = null;
                return;
            }
            if (e.target.dataset.cfm === 'ok') {
                root.classList.remove('show');
                root.setAttribute('aria-hidden', 'true');

                // Submit form atau ikuti link
                if (targetEl) {
                    const form = targetEl.closest('form');
                    if (form) form.submit();
                    else if (targetEl.tagName.toLowerCase() === 'a') {
                        window.location.href = targetEl.getAttribute('href');
                    }
                    targetEl = null;
                }
            }
        });

        // ESC untuk menutup
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && root.classList.contains('show')) {
                root.classList.remove('show');
                root.setAttribute('aria-hidden', 'true');
                targetEl = null;
            }
        });
    })();
</script>

<body>
    @auth
    <div class="container-scroller">
        <!-- Navbar -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/kasirku.svg') }}" class="mr-2" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/kasirku.svg') }}" alt="logo" />
                </a>
            </div>

            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav navbar-nav-right">
                    {{-- Profil ringkas (tanpa dropdown) --}}
                    <li class="nav-item d-flex align-items-center">
                        @php
                        $raw = Auth::user()->profile_photo ?? null;
                        $rel = $raw && \Illuminate\Support\Str::startsWith($raw, 'public/')
                        ? \Illuminate\Support\Str::after($raw, 'public/')
                        : $raw;

                        $exists = $rel && \Illuminate\Support\Facades\Storage::disk('public')->exists($rel);
                        $photoUrl = $exists
                        ? \Illuminate\Support\Facades\Storage::url($rel)
                        : asset('images/faces/face28.jpg');
                        @endphp

                        <span class="d-none d-lg-inline-block text-gray mr-2">{{ Auth::user()->name }}</span>
                        <img class="ms-2"
                            src="{{ $photoUrl }}"
                            alt="profile"
                            style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                    </li>
                </ul>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                    type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    {{-- Dashboard --}}
                    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="ti-home menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>

                    {{-- Kasir --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kasir') }}">
                            <i class="ti-credit-card menu-icon"></i>
                            <span class="menu-title">Kasir</span>
                        </a>
                    </li>

                    {{-- Riwayat Transaksi --}}
                    <li class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('transactions.index') }}">
                            <i class="ti-receipt menu-icon"></i>
                            <span class="menu-title">Riwayat Transaksi</span>
                        </a>
                    </li>

                    @if(Auth::user()->hasAdminPrivileges())
                    {{-- Manajemen Produk (collapse) --}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#products-menu" aria-expanded="false" aria-controls="products-menu">
                            <i class="ti-package menu-icon"></i>
                            <span class="menu-title">Manajemen Produk</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="products-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Semua Produk</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('products.create') }}">Tambah Produk</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Manajemen Kategori --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="ti-tag menu-icon"></i>
                            <span class="menu-title">Manajemen Kategori</span>
                        </a>
                    </li>

                    {{-- Manajemen Karyawan (collapse) --}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#employees-menu" aria-expanded="false" aria-controls="employees-menu">
                            <i class="ti-user menu-icon"></i>
                            <span class="menu-title">Manajemen Karyawan</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="employees-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('employees.index') }}">Semua Karyawan</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('employees.create') }}">Tambah Karyawan</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Laporan Penjualan --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}">
                            <i class="ti-bar-chart menu-icon"></i>
                            <span class="menu-title">Laporan Penjualan</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->canManageUsers())
                    {{-- Manajemen User (collapse) --}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#users-menu" aria-expanded="false" aria-controls="users-menu">
                            <i class="ti-id-badge menu-icon"></i>
                            <span class="menu-title">Manajemen User</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="users-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Semua User</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Tambah User Baru</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="ti-power-off menu-icon"></i>
                            <span class="menu-title">Logout</span>
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @endif
                </ul>
            </nav>

            <!-- Main Content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @hasSection('page_title')
                    <div class="page-header">
                        <h1>@yield('page_title')</h1>
                        @yield('page_actions')
                    </div>
                    @endif

                    @yield('content')
                </div>

                <!-- Footer -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            Copyright © {{ date('Y') }}. All rights reserved.
                        </span>
                    </div>
                </footer>
            </div>

            <!-- Notifications -->
            @include('partials.notifications')
        </div>
    </div>
    @else
    <!-- Jika belum login, redirect ke login -->
    <script>
        window.location = "{{ route('login') }}";
    </script>
    @endauth

    <!-- JavaScript -->
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('js/sidebar-fix.js') }}"></script>
    <script src="{{ asset('js/notify-compat.js') }}?v=1"></script>
    <script src="{{ asset('js/ui-enhancements.js') }}?v=1"></script>
    @yield('scripts')

</body>

</html>

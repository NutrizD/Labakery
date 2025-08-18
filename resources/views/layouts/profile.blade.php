<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Toko Kue') }} - {{ $page_title ?? 'Profile' }}</title>
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <!-- Custom Color Scheme -->
    <link rel="stylesheet" href="{{ asset('css/custom-colors.css') }}?v=sellora-blue-1">
    <!-- Dashboard Custom Styling -->
    <link rel="stylesheet" href="{{ asset('css/dashboard-custom.css') }}?v=sellora-blue-1">
    <!-- Sellora Theme Override -->
    <link rel="stylesheet" href="{{ asset('css/sellora-theme-override.css') }}?v=sellora-blue-1">
    <!-- Global Sellora Override -->
    <link rel="stylesheet" href="{{ asset('css/global-sellora-override.css') }}?v=sellora-blue-1">
    <!-- End plugin css for this page -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    
</head>
<body>
    @auth
    <div class="profile-container">
        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" class="back-button" title="Kembali ke Dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><polyline points="15 18 9 12 15 6"/><line x1="9" y1="12" x2="21" y2="12"/></svg>
        </a>
        
        <div class="profile-content">
            <!-- Profile Header -->
            <div class="profile-header">
                <h1><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary me-2 icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>{{ $page_title ?? 'Edit Profil' }}</h1>
                <p>Kelola informasi profil dan keamanan akun Anda</p>
            </div>
            
            <!-- Main Content -->
            @yield('content')
        </div>
    </div>
    @else
        <!-- Jika pengguna belum login, arahkan ke halaman login -->
        <script>window.location = "{{ route('login') }}";</script>
    @endauth

    <!-- JavaScript dari template Anda -->
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LaporPakRT Dashboard' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    @livewireStyles
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }
        .card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem 1.5rem 1rem;
            border-radius: 16px 16px 0 0 !important;
        }
        .info-box {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03) !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }
        .info-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
        }
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        .btn-sm { padding: 0.25rem 0.5rem; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .table > :not(caption) > * > * { padding: 1rem 1.5rem; }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: rgba(248, 250, 252, 0.5); }
        .table-hover tbody tr:hover { background-color: rgba(59, 130, 246, 0.03); }
        .app-sidebar { box-shadow: 2px 0 20px rgba(0,0,0,0.04) !important; }
        .nav-link.active {
            background-color: rgba(255,255,255,0.1) !important;
            border-radius: 8px;
        }
        .app-content-header { padding: 2rem 0.5rem; }
        h3 { font-weight: 600; color: #1e293b; }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="bi bi-list"></i> </a> </li>
                </ul>
            </div>
        </nav>
        <!-- Main Sidebar Container -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <i class="bi bi-shield-check text-primary fs-3 me-2"></i>
                    <span class="brand-text fw-light">LaporPakRT</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        @if(auth()->user()->role == 'rt_admin')
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('warga.index') }}" class="nav-link {{ request()->is('warga*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Data Warga</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tamu.index') }}" class="nav-link {{ request()->is('tamu*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-book"></i>
                                <p>Buku Tamu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ocr.test') }}" class="nav-link {{ request()->is('ocr-test') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-upc-scan"></i>
                                <p>Test OCR KTP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('surat.index') }}" class="nav-link {{ request()->is('surat*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Surat & Dokumen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kas.index') }}" class="nav-link {{ request()->is('kas*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-wallet2"></i>
                                <p>Kas RT</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shield-lock"></i>
                                <p>Manajemen Akses</p>
                            </a>
                        </li>
                        @elseif(auth()->user()->role == 'warga')
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-house"></i>
                                <p>Beranda Warga</p>
                            </a>
                        </li>
                        @elseif(auth()->user()->role == 'security')
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard Security</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tamu.index') }}" class="nav-link {{ request()->is('tamu*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-book"></i>
                                <p>Buku Tamu</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-header">AKUN</li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                            </form>
                            <a href="#" class="nav-link text-danger" onclick="document.getElementById('logout-form').submit();">
                                <i class="nav-icon bi bi-box-arrow-right"></i>
                                <p>Keluar</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <main class="app-main">
            {{ $slot }}
        </main>
    </div>
    
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    @livewireScripts

    <!-- Global Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
        <div id="livewire-toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="livewire-toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const toastEl = document.getElementById('livewire-toast');
            const toastBody = document.getElementById('livewire-toast-body');
            const toast = new bootstrap.Toast(toastEl, { delay: 5000 });

            Livewire.on('notify', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                const type = data.type || 'info';
                const message = data.message || '';

                toastEl.className = 'toast align-items-center text-white border-0 bg-' + type;
                toastBody.textContent = message;
                toast.show();
            });
        });
    </script>
</body>
</html>

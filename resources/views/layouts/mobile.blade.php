<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>{{ $title ?? 'LaporPakRT Mobile' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap 5 (Without AdminLTE) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @livewireStyles
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdf4 100%);
            background-attachment: fixed;
            color: var(--text-main);
            padding-bottom: calc(85px + env(safe-area-inset-bottom));
            -webkit-font-smoothing: antialiased;
        }
        
        /* Glassmorphism Header */
        .mobile-header {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: calc(15px + env(safe-area-inset-top)) 20px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mobile-header-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            background: linear-gradient(135deg, var(--text-main), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Glassmorphism Bottom Nav */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(0,0,0,0.05);
            z-index: 1040;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            padding-bottom: calc(10px + env(safe-area-inset-bottom));
            box-shadow: 0 -4px 24px rgba(0,0,0,0.04);
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            flex: 1;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-item i {
            font-size: 1.4rem;
            margin-bottom: 4px;
            transition: transform 0.2s;
        }
        .nav-item.active {
            color: var(--primary);
        }
        .nav-item.active i {
            transform: translateY(-2px);
        }
        
        /* Soft Cards */
        .soft-card {
            background: var(--card-bg);
            border-radius: 20px;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            padding: 16px;
            margin-bottom: 16px;
            transition: transform 0.2s;
        }
        .soft-card:active {
            transform: scale(0.98);
        }
        
        /* Floating Action Button (FAB) */
        .fab {
            position: fixed;
            bottom: calc(90px + env(safe-area-inset-bottom));
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 28px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.4);
            border: none;
            z-index: 1050;
            transition: transform 0.2s;
            font-size: 1.5rem;
        }
        .fab:active {
            transform: scale(0.92);
        }
        
        /* Utilities */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    
    <header class="mobile-header">
        <h1 class="mobile-header-title">{{ $title ?? 'LaporPakRT' }}</h1>
        <div>
            @if(auth()->user())
                <div class="bg-light rounded-circle d-flex justify-content-center align-items-center fw-bold text-primary shadow-sm" style="width: 36px; height: 36px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
    </header>

    <main class="container-fluid px-3 py-4">
        {{ $slot }}
    </main>

    <nav class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house{{ request()->is('dashboard') ? '-fill' : '' }} text-primary"></i>
            <span>Beranda</span>
        </a>
        
        @if(auth()->user() && (auth()->user()->role == 'rt_admin' || auth()->user()->isSuperadmin()))
        <a href="{{ route('warga.index') }}" class="nav-item {{ request()->is('warga*') ? 'active' : '' }}">
            <i class="bi bi-people{{ request()->is('warga*') ? '-fill' : '' }} text-info"></i>
            <span>Warga</span>
        </a>
        @endif
        
        @if(auth()->user() && (auth()->user()->role == 'rt_admin' || auth()->user()->isSuperadmin() || auth()->user()->role == 'security'))
        <a href="{{ route('tamu.index') }}" class="nav-item {{ request()->is('tamu*') ? 'active' : '' }}">
            <i class="bi bi-book{{ request()->is('tamu*') ? '-fill' : '' }} text-success"></i>
            <span>Tamu</span>
        </a>
        @endif
        
        <a href="#" class="nav-item text-secondary" data-bs-toggle="offcanvas" data-bs-target="#masterMenu" aria-controls="masterMenu">
            <i class="bi bi-grid-fill text-warning"></i>
            <span>Menu</span>
        </a>
    </nav>

    <!-- Master Menu Offcanvas -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="masterMenu" aria-labelledby="masterMenuLabel" style="border-radius: 24px 24px 0 0; min-height: 40vh; max-height: 80vh;">
        <div class="offcanvas-header pb-0 border-0">
            <h6 class="offcanvas-title fw-bold text-dark" id="masterMenuLabel">Menu Utama</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pt-3 pb-5">
            <div class="row g-3">
                @if(auth()->user() && (auth()->user()->role == 'rt_admin' || auth()->user()->isSuperadmin()))
                <div class="col-4 text-center">
                    <a href="{{ route('kas.index') }}" class="text-decoration-none d-block">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                            <i class="bi bi-wallet2 text-success fs-3"></i>
                        </div>
                        <span class="text-dark small fw-medium">Kas RT</span>
                    </a>
                </div>
                <div class="col-4 text-center">
                    <a href="#" class="text-decoration-none d-block">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                            <i class="bi bi-envelope-paper text-primary fs-3"></i>
                        </div>
                        <span class="text-dark small fw-medium">Surat</span>
                    </a>
                </div>
                <div class="col-4 text-center">
                    <a href="#" class="text-decoration-none d-block">
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                            <i class="bi bi-gear text-secondary fs-3"></i>
                        </div>
                        <span class="text-dark small fw-medium">Pengaturan</span>
                    </a>
                </div>
                @endif
                <div class="col-4 text-center">
                    <a href="#" class="text-decoration-none d-block">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                            <i class="bi bi-person-badge text-info fs-3"></i>
                        </div>
                        <span class="text-dark small fw-medium">Profil Saya</span>
                    </a>
                </div>
                <div class="col-4 text-center">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form-offcanvas" class="d-none">@csrf</form>
                    <a href="#" class="text-decoration-none d-block" onclick="event.preventDefault(); document.getElementById('logout-form-offcanvas').submit();">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                            <i class="bi bi-box-arrow-right text-danger fs-3"></i>
                        </div>
                        <span class="text-danger small fw-medium">Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    
    <!-- Mobile Toast -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 2000; margin-top: env(safe-area-inset-top);">
        <div id="livewire-toast-mobile" class="toast align-items-center text-white border-0 shadow-lg rounded-pill" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex px-2">
                <div class="toast-body fw-medium" id="livewire-toast-body-mobile"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const toastEl = document.getElementById('livewire-toast-mobile');
            const toastBody = document.getElementById('livewire-toast-body-mobile');
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });

            Livewire.on('notify', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                const type = data.type || 'info';
                const message = data.message || '';

                toastEl.className = 'toast align-items-center text-white border-0 shadow-lg rounded-pill bg-' + (type === 'error' ? 'danger' : type);
                toastBody.textContent = message;
                toast.show();
            });
        });
    </script>
</body>
</html>

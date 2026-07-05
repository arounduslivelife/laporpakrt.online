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
            background-color: var(--bg-color);
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
            <i class="bi bi-house{{ request()->is('dashboard') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>
        
        @if(auth()->user() && (auth()->user()->role == 'rt_admin' || auth()->user()->isSuperadmin()))
        <a href="{{ route('warga.index') }}" class="nav-item {{ request()->is('warga*') ? 'active' : '' }}">
            <i class="bi bi-people{{ request()->is('warga*') ? '-fill' : '' }}"></i>
            <span>Warga</span>
        </a>
        <a href="{{ route('kas.index') }}" class="nav-item {{ request()->is('kas*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Kas RT</span>
        </a>
        @endif
        
        @if(auth()->user() && (auth()->user()->role == 'rt_admin' || auth()->user()->isSuperadmin() || auth()->user()->role == 'security'))
        <a href="{{ route('tamu.index') }}" class="nav-item {{ request()->is('tamu*') ? 'active' : '' }}">
            <i class="bi bi-book{{ request()->is('tamu*') ? '-fill' : '' }}"></i>
            <span>Tamu</span>
        </a>
        @endif
        
        <form action="{{ route('logout') }}" method="POST" id="logout-form-mobile" class="d-none">@csrf</form>
        <a href="#" class="nav-item text-danger opacity-75" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar</span>
        </a>
    </nav>

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

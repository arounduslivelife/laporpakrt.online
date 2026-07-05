<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>LaporPakRT - Digitalisasi RT Modern</title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS & Icons -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- AOS CSS (Animasi Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #070914; /* Deep midnight blue */
            --bg-surface: rgba(255, 255, 255, 0.03);
            --bg-surface-hover: rgba(255, 255, 255, 0.06);
            --primary-neon: #00f0ff;
            --secondary-neon: #7000ff;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-border-hover: rgba(0, 240, 255, 0.4);
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-dark);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        /* Ambient Glowing Backgrounds */
        .ambient-light {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.5;
            pointer-events: none;
            animation: pulse-glow 8s infinite alternate;
        }
        
        @keyframes pulse-glow {
            0% { transform: scale(1); opacity: 0.4; }
            100% { transform: scale(1.1); opacity: 0.6; }
        }

        .orb-1 {
            top: -10%; left: -10%;
            width: 50vw; height: 50vw;
            background: radial-gradient(circle, rgba(112,0,255,0.4) 0%, rgba(112,0,255,0) 70%);
        }

        .orb-2 {
            bottom: -20%; right: -10%;
            width: 60vw; height: 60vw;
            background: radial-gradient(circle, rgba(0,240,255,0.25) 0%, rgba(0,240,255,0) 70%);
            animation-delay: -4s;
        }

        /* Custom Glassmorphism Utilities */
        .glass-panel {
            background: var(--bg-surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .glass-panel:hover {
            border-color: var(--glass-border-hover);
            background: var(--bg-surface-hover);
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(0, 240, 255, 0.15);
        }

        /* Navbar */
        .navbar-glass {
            background: rgba(7, 9, 20, 0.6) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar-glass.scrolled {
            padding: 10px 0;
            background: rgba(7, 9, 20, 0.85) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .brand-icon {
            background: linear-gradient(135deg, var(--primary-neon), var(--secondary-neon));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            transition: color 0.3s, transform 0.3s;
            margin: 0 10px;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        /* Custom Toggler for Mobile */
        .navbar-toggler {
            border: none;
            color: white;
            padding: 0;
        }
        .navbar-toggler:focus { box-shadow: none; }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Mobile Menu Glass Adjustment */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(15, 20, 40, 0.95);
                backdrop-filter: blur(20px);
                border-radius: 16px;
                padding: 20px;
                margin-top: 15px;
                border: 1px solid var(--glass-border);
            }
            .nav-link { margin: 10px 0; }
        }

        /* Buttons */
        .btn-neon {
            background: linear-gradient(45deg, var(--primary-neon), #0073ff);
            color: #000;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-neon::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(45deg, #0073ff, var(--primary-neon));
            z-index: -1;
            transition: opacity 0.3s;
            opacity: 0;
        }

        .btn-neon:hover {
            color: #000;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.6);
        }

        .btn-neon:hover::before { opacity: 1; }

        .btn-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 12px 28px;
            transition: all 0.3s ease;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            padding: 180px 0 100px;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .text-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .text-gradient-primary {
            background: linear-gradient(to right, var(--primary-neon), #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 500px;
        }

        /* Floating 3D Elements UI Mockup */
        .hero-graphics {
            position: relative;
            perspective: 1000px;
            height: 400px;
            width: 100%;
        }

        @keyframes float-ui-1 {
            0% { transform: translateY(0px) rotateY(-15deg) rotateX(5deg); }
            50% { transform: translateY(-20px) rotateY(-10deg) rotateX(2deg); }
            100% { transform: translateY(0px) rotateY(-15deg) rotateX(5deg); }
        }
        
        @keyframes float-ui-2 {
            0% { transform: translateZ(50px) translateY(0px) rotateY(-15deg); }
            50% { transform: translateZ(50px) translateY(15px) rotateY(-20deg); }
            100% { transform: translateZ(50px) translateY(0px) rotateY(-15deg); }
        }

        .mockup-main {
            position: absolute;
            top: 20px;
            right: 10%;
            width: 80%;
            max-width: 450px;
            height: auto;
            border-radius: 20px;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: -20px 20px 50px rgba(0,0,0,0.5), inset 0 0 0 1px rgba(255,255,255,0.05);
            transform: rotateY(-15deg) rotateX(5deg);
            transform-style: preserve-3d;
            animation: float-ui-1 6s ease-in-out infinite;
            padding: 20px;
        }

        .mockup-widget {
            position: absolute;
            bottom: -20px;
            left: -10%;
            width: 200px;
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 240, 255, 0.3);
            border-radius: 16px;
            padding: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4), 0 0 20px rgba(0,240,255,0.1);
            transform: translateZ(50px) rotateY(-15deg);
            animation: float-ui-2 7s ease-in-out infinite alternate;
        }

        /* Feature Icons */
        .feature-icon-wrapper {
            width: 60px; height: 60px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary-neon);
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }

        .glass-panel:hover .feature-icon-wrapper {
            background: var(--primary-neon);
            color: #000;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.5);
            transform: scale(1.1);
        }

        /* Pricing Area */
        .pricing-pro {
            position: relative;
        }
        .pricing-pro::before {
            content: '';
            position: absolute;
            top: -2px; left: -2px; right: -2px; bottom: -2px;
            background: linear-gradient(135deg, var(--primary-neon), var(--secondary-neon));
            z-index: -1;
            border-radius: 26px;
            filter: blur(8px);
            opacity: 0.6;
            transition: opacity 0.3s;
        }
        .pricing-pro:hover::before { opacity: 1; filter: blur(12px); }

        .pricing-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(90deg, #ff007f, #7000ff);
            color: white;
            font-size: 0.8rem;
            font-weight: 800;
            padding: 6px 16px;
            border-radius: 20px;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(255, 0, 127, 0.4);
        }

        /* Footer */
        footer {
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.3);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-neon); }

        /* Helpers */
        .text-neon { color: var(--primary-neon); text-shadow: 0 0 10px rgba(0,240,255,0.5); }
    </style>
</head>
<body oncontextmenu="return false;" onkeydown="if(event.keyCode==123) return false;">
    
    <!-- Anti-Inspect Script -->
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function(e) {
            if(e.keyCode == 123) { return false; } 
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) { return false; }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) { return false; }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) { return false; }
            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) { return false; }
        }
    </script>

    <!-- Background Orbs -->
    <div class="ambient-light orb-1"></div>
    <div class="ambient-light orb-2"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand brand-font d-flex align-items-center" href="#">
                <i class="bi bi-hexagon-half brand-icon me-2 fs-3"></i> 
                <span>LaporPak<span class="text-neon">RT</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#harga">Harga</a></li>
                </ul>
                <div class="d-flex mt-3 mt-lg-0 align-items-center gap-3">
                    <a href="{{ route('login') }}" class="text-white text-decoration-none fw-medium hover-neon">Log In</a>
                    <a href="{{ route('register') }}" class="btn-neon">Mulai Sistem</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start" data-aos="fade-right" data-aos-duration="1000">
                    <div class="d-inline-flex align-items-center mb-3 px-3 py-1 rounded-pill" style="background: rgba(0, 240, 255, 0.1); border: 1px solid rgba(0, 240, 255, 0.2);">
                        <span class="spinner-grow spinner-grow-sm text-neon me-2" style="width: 8px; height: 8px;"></span>
                        <span class="text-neon fw-semibold" style="font-size: 0.85rem; letter-spacing: 1px;">SISTEM MANAJEMEN RT V2.0</span>
                    </div>
                    
                    <h1 class="hero-title fw-black brand-font">
                        Masa Depan <br>
                        <span class="text-gradient-primary">Ekosistem Warga.</span>
                    </h1>
                    
                    <p class="hero-subtitle mx-auto mx-lg-0">
                        Platform sentralisasi data warga, keamanan (buku tamu AI), dan transparansi kas. Dirancang eksklusif dengan keamanan tinggi untuk RT modern.
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('register') }}" class="btn-neon d-inline-flex align-items-center justify-content-center">
                            Deploy Sekarang <i class="bi bi-rocket-takeoff ms-2"></i>
                        </a>
                        <a href="#fitur" class="btn-glass d-inline-flex align-items-center justify-content-center">
                            Jelajahi Modul
                        </a>
                    </div>
                    
                    <div class="mt-5 d-flex gap-4 justify-content-center justify-content-lg-start">
                        <div>
                            <h4 class="text-white brand-font mb-0 fw-bold">100%</h4>
                            <small class="text-muted">Cloud Secured</small>
                        </div>
                        <div class="border-start border-secondary opacity-25"></div>
                        <div>
                            <h4 class="text-white brand-font mb-0 fw-bold">PWA</h4>
                            <small class="text-muted">Offline Support</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="zoom-out-left" data-aos-duration="1200">
                    <!-- 3D Glassmorphism Graphics -->
                    <div class="hero-graphics d-none d-md-block">
                        <!-- Main Dashboard Mockup -->
                        <div class="mockup-main">
                            <div class="d-flex justify-content-between mb-4 border-bottom border-secondary pb-3 border-opacity-25">
                                <div class="d-flex gap-2">
                                    <div class="rounded-circle bg-danger opacity-75" style="width:12px; height:12px;"></div>
                                    <div class="rounded-circle bg-warning opacity-75" style="width:12px; height:12px;"></div>
                                    <div class="rounded-circle bg-success opacity-75" style="width:12px; height:12px;"></div>
                                </div>
                                <div class="text-muted" style="font-size: 0.7rem;">RT/RW Admin Node</div>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="rounded p-3 bg-white bg-opacity-5 border border-white border-opacity-10">
                                        <div class="text-muted mb-1" style="font-size: 0.7rem;">Total Warga</div>
                                        <div class="fs-4 brand-font fw-bold text-white">2,405</div>
                                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.1);">
                                            <div class="progress-bar bg-info" style="width: 75%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded p-3 bg-white bg-opacity-5 border border-white border-opacity-10">
                                        <div class="text-muted mb-1" style="font-size: 0.7rem;">Kas Aktif</div>
                                        <div class="fs-4 brand-font fw-bold text-neon">Rp 15M</div>
                                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.1);">
                                            <div class="progress-bar" style="width: 60%; background: var(--secondary-neon);"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3 p-2 rounded bg-white bg-opacity-5">
                                <div class="rounded bg-success bg-opacity-25 p-2 me-3 text-success"><i class="bi bi-shield-check"></i></div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-white" style="font-size:0.85rem;">Sistem Enkripsi</span>
                                        <span class="text-success" style="font-size:0.8rem;">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Small Widget -->
                        <div class="mockup-widget">
                            <div class="d-flex align-items-center mb-2">
                                <div class="spinner-grow text-neon spinner-grow-sm me-2"></div>
                                <span class="text-white fw-bold brand-font" style="font-size:0.9rem;">AI Camera Scan</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem; line-height: 1.3;">Pemindaian KTP Tamu mendeteksi identitas dalam 1.2 detik.</div>
                            <div class="mt-3 bg-white bg-opacity-10 rounded p-1 position-relative overflow-hidden" style="height: 4px;">
                                <div class="position-absolute top-0 bottom-0 bg-info" style="width: 40%; animation: slideRight 2s infinite ease-in-out;"></div>
                            </div>
                        </div>
                        
                        <style>@keyframes slideRight { 0%{left: -40%;} 100%{left: 100%;} }</style>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-5 my-5 position-relative z-1">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-neon fw-bold tracking-wider mb-2">MODUL INTI</h6>
                <h2 class="brand-font fw-black display-5 text-white">Arsitektur Fitur Premium</h2>
                <p class="text-muted mx-auto mt-3" style="max-width: 600px;">Bukan sekadar pencatatan biasa. Kami membangun ekosistem cerdas untuk memudahkan birokrasi dan keamanan lingkungan Anda.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="glass-panel p-4 h-100">
                        <div class="feature-icon-wrapper"><i class="bi bi-database-fill"></i></div>
                        <h4 class="brand-font fw-bold text-white mb-3">Cloud Database</h4>
                        <p class="text-muted" style="font-size: 0.95rem;">Penyimpanan data demografi KK dan NIK yang terpusat dengan enkripsi tingkat lanjut. Akses instan di mana pun.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="glass-panel p-4 h-100">
                        <div class="feature-icon-wrapper"><i class="bi bi-upc-scan"></i></div>
                        <h4 class="brand-font fw-bold text-white mb-3">AI Guest Scanner</h4>
                        <p class="text-muted" style="font-size: 0.95rem;">Buku tamu canggih yang dilengkapi pemindai KTP cerdas (OCR) dan deteksi koordinat GPS (Geotagging).</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="glass-panel p-4 h-100">
                        <div class="feature-icon-wrapper"><i class="bi bi-wallet2"></i></div>
                        <h4 class="brand-font fw-bold text-white mb-3">Smart Finance</h4>
                        <p class="text-muted" style="font-size: 0.95rem;">Manajemen kas transparan dengan riwayat audit (*audit trail*). Laporan PDF/Excel instan dalam hitungan detik.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="glass-panel p-4 h-100">
                        <div class="feature-icon-wrapper"><i class="bi bi-file-earmark-lock-fill"></i></div>
                        <h4 class="brand-font fw-bold text-white mb-3">Auto-Purge System</h4>
                        <p class="text-muted" style="font-size: 0.95rem;">Sistem pembersih otomatis (*cron-job*) yang menghancurkan berkas fisik dari server saat data warga ditarik demi privasi mutlak.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="glass-panel p-4 h-100">
                        <div class="feature-icon-wrapper"><i class="bi bi-app-indicator"></i></div>
                        <h4 class="brand-font fw-bold text-white mb-3">PWA Native Feel</h4>
                        <p class="text-muted" style="font-size: 0.95rem;">Install di layar Home layaknya aplikasi native tanpa perlu ke App Store. Mendukung pengoperasian dalam sinyal lemah.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="glass-panel p-4 h-100">
                        <div class="feature-icon-wrapper"><i class="bi bi-diagram-3-fill"></i></div>
                        <h4 class="brand-font fw-bold text-white mb-3">Role-Based Access</h4>
                        <p class="text-muted" style="font-size: 0.95rem;">Pembagian otoritas absolut antara Warga, Satpam/Keamanan, dan Admin RT. Semua aktivitas terlacak rapi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="py-5 my-5 position-relative z-1">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-neon fw-bold tracking-wider mb-2">INVESTASI</h6>
                <h2 class="brand-font fw-black display-5 text-white">Lisensi Akses</h2>
                <p class="text-muted mx-auto mt-3" style="max-width: 600px;">Pilih tingkat otorisasi yang sesuai dengan ekosistem RT Anda.</p>
            </div>

            <div class="row g-4 justify-content-center mt-4">
                <!-- Basic Plan -->
                <div class="col-lg-4 col-md-6" data-aos="fade-right">
                    <div class="glass-panel p-5 h-100 position-relative">
                        <h4 class="brand-font fw-bold text-white mb-2">Protokol Dasar</h4>
                        <p class="text-muted mb-4 small">Untuk kluster/RT skala kecil.</p>
                        
                        <div class="mb-4">
                            <span class="fs-1 fw-black text-white brand-font">Rp 0</span>
                            <span class="text-muted">/selamanya</span>
                        </div>
                        
                        <ul class="list-unstyled text-start mb-5" style="font-size: 0.95rem;">
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-light">Maksimal 50 Keluarga</span></li>
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-light">Buku Tamu Manual</span></li>
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-light">Manajemen Kas Dasar</span></li>
                            <li class="mb-3 d-flex opacity-50"><i class="bi bi-x-lg text-danger me-3"></i> <span class="text-muted">AI KTP Scanner</span></li>
                            <li class="mb-3 d-flex opacity-50"><i class="bi bi-x-lg text-danger me-3"></i> <span class="text-muted">Dukungan Prioritas</span></li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn-glass w-100 text-center d-block text-decoration-none">Inisialisasi Gratis</a>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="glass-panel p-5 h-100 pricing-pro">
                        <div class="pricing-badge">ELITE TIER</div>
                        <h4 class="brand-font fw-bold text-neon mb-2">Protokol Elite</h4>
                        <p class="text-muted mb-4 small">Membuka seluruh batasan sistem.</p>
                        
                        <div class="mb-4">
                            <span class="fs-1 fw-black text-white brand-font">Rp 99K</span>
                            <span class="text-muted">/bulan</span>
                        </div>
                        
                        <ul class="list-unstyled text-start mb-5" style="font-size: 0.95rem;">
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-white fw-bold">Unlimited Data Keluarga</span></li>
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-light">AI KTP Scanner & Geotag</span></li>
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-light">Export Audit Eksekutif</span></li>
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-light">Auto-Purge Security</span></li>
                            <li class="mb-3 d-flex"><i class="bi bi-check-lg text-neon me-3"></i> <span class="text-light">Dukungan Teknis 24/7</span></li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn-neon w-100 text-center d-block text-decoration-none">Upgrade ke Elite</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 mb-5 position-relative z-1">
        <div class="container">
            <div class="glass-panel p-5 text-center overflow-hidden position-relative" data-aos="zoom-in" style="border: 1px solid rgba(0, 240, 255, 0.3);">
                <!-- Glow Effect inside CTA -->
                <div class="position-absolute" style="top:50%; left:50%; transform:translate(-50%,-50%); width:300px; height:300px; background:radial-gradient(circle, rgba(112,0,255,0.4) 0%, rgba(0,0,0,0) 70%); z-index:-1;"></div>
                
                <h2 class="brand-font fw-black mb-3 text-white">Siap Melakukan Migrasi Sistem?</h2>
                <p class="text-muted mx-auto mb-5" style="max-width: 500px;">Tinggalkan sistem manual kuno. Waktunya membawa lingkungan Anda ke era digitalisasi penuh keamanan.</p>
                <a href="{{ route('register') }}" class="btn-neon px-5 py-3 fs-6 d-inline-block text-decoration-none">Mulai Proses Booting</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="pt-5 pb-4 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h5 class="brand-font fw-bold mb-2 text-white"><i class="bi bi-hexagon-half text-neon me-1"></i> LaporPak<span class="text-neon">RT</span></h5>
                    <p class="text-muted small mb-0">Infrastruktur Digitalisasi Rukun Tetangga #1.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} LaporPakRT Systems. Securely Encrypted.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50 });

        window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.navbar-glass');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporPakRT - Digitalisasi RT Modern</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS & Icons -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- AOS CSS (Animasi Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0061ff;
            --secondary-color: #60efff;
            --text-dark: #1e293b;
            --text-light: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
            background-color: #f8fafc;
        }

        /* Glassmorphism Navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), #00d2ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-dark) !important;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* Hero Section with Mesh Gradient */
        .hero-section {
            position: relative;
            padding: 140px 0 100px;
            background: radial-gradient(circle at 10% 20%, rgba(96, 239, 255, 0.1) 0%, rgba(0, 97, 255, 0.05) 90.2%);
            overflow: hidden;
        }

        /* Floating Animation */
        @keyframes floating {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        /* Village Animations */
        @keyframes spin { 100% { transform: rotate(360deg); } }
        @keyframes slideRight { 0% { transform: translateX(-50px); } 100% { transform: translateX(900px); } }
        @keyframes slideLeft { 0% { transform: translateX(50px) scaleX(-1); } 100% { transform: translateX(-900px) scaleX(-1); } }

        .hero-illustration {
            animation: floating 4s ease-in-out infinite;
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 20px 30px rgba(0,97,255,0.15));
        }

        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }

        .hero-title span {
            color: var(--primary-color);
        }

        .btn-custom {
            padding: 0.8rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, #00d2ff 100%);
            border: none;
            color: white;
            box-shadow: 0 10px 20px rgba(0, 97, 255, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0, 97, 255, 0.4);
            color: white;
        }

        /* Features Section */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.4s ease;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: rgba(0,97,255,0.1);
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(0,97,255,0.1) 0%, rgba(96,239,255,0.1) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover .icon-box {
            background: linear-gradient(135deg, var(--primary-color) 0%, #00d2ff 100%);
            color: white;
            transform: scale(1.1) rotate(-5deg);
        }

        /* Pricing Section */
        .pricing-section {
            background-color: white;
            position: relative;
        }

        .pricing-card {
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            background: white;
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
        }

        .pricing-card.pro {
            border: 2px solid var(--primary-color);
            box-shadow: 0 20px 40px rgba(0,97,255,0.15);
            transform: scale(1.05);
            z-index: 2;
        }

        .pricing-card.pro:hover {
            transform: scale(1.05) translateY(-10px);
        }

        .badge-popular {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #ff6b6b, #ff8e8b);
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 5px 15px rgba(255,107,107,0.3);
        }

        .price-text {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .feature-list li {
            padding: 10px 0;
            color: var(--text-light);
            display: flex;
            align-items: center;
        }

        .feature-list i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background: var(--text-dark);
            color: white;
        }
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-shield-fill-check me-2 fs-3 text-primary"></i> LaporPakRT
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#harga">Harga</a></li>
                </ul>
                <div class="d-flex mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-dark fw-bold me-3">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-custom btn-primary-custom">Daftar RT</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <div class="d-inline-block mb-3 px-3 py-1 rounded-pill" style="background: rgba(0,97,255,0.1); color: var(--primary-color); font-weight: 600; font-size: 0.9rem;">
                        <i class="bi bi-star-fill me-1"></i> Platform SaaS RT #1 di Indonesia
                    </div>
                    <h1 class="hero-title">Digitalisasi RT Anda Kini Makin <span>Mudah & Modern</span></h1>
                    <p class="lead mb-4 text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                        Tinggalkan cara manual! Kelola data warga, catat buku tamu, dan atur kas RT dalam satu genggaman. Cepat, aman, dan bisa diakses kapan saja.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('register') }}" class="btn btn-custom btn-primary-custom d-flex align-items-center">
                            Mulai Gratis <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#fitur" class="btn btn-custom btn-outline-secondary bg-white text-dark border-0 shadow-sm d-flex align-items-center">
                            Pelajari Fitur
                        </a>
                    </div>
                    
                    <div class="mt-5 d-flex align-items-center gap-4 text-muted">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i> Gratis Selamanya
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i> Offline Support
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <!-- Pure HTML/CSS Dashboard Illustration -->
                    <div class="hero-illustration position-relative mt-4 mt-lg-0">
                        <!-- Main Dashboard Card -->
                        <div class="bg-white rounded-4 shadow-lg p-4 position-relative text-start" style="z-index: 2; border: 1px solid rgba(0,0,0,0.05); width: 100%; max-width: 450px; margin: 0 auto;">
                            <!-- Header Mockup -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle" style="width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary-color), #00d2ff);"></div>
                                    <div class="ms-3">
                                        <div class="rounded bg-secondary opacity-25 mb-2" style="width: 100px; height: 10px;"></div>
                                        <div class="rounded bg-secondary opacity-25" style="width: 60px; height: 8px;"></div>
                                    </div>
                                </div>
                                <div class="rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-1" style="font-size: 0.8rem; font-weight: 600;">Admin RT</div>
                            </div>
                            
                            <!-- Charts Mockup -->
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="rounded-3 p-3 h-100" style="background: rgba(0,97,255,0.05);">
                                        <div class="rounded bg-primary opacity-25 mb-2" style="width: 50px; height: 8px;"></div>
                                        <div class="fs-3 fw-bold text-dark">156</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3 h-100" style="background: rgba(96,239,255,0.1);">
                                        <div class="rounded bg-info opacity-50 mb-2" style="width: 50px; height: 8px;"></div>
                                        <div class="fs-3 fw-bold text-dark">42</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- List Mockup -->
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-circle fs-3 text-muted opacity-50"></i>
                                <div class="ms-3 w-100">
                                    <div class="rounded bg-secondary opacity-25 mb-2 w-75" style="height: 8px;"></div>
                                    <div class="rounded bg-secondary opacity-25 w-50" style="height: 6px;"></div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle fs-3 text-muted opacity-50"></i>
                                <div class="ms-3 w-100">
                                    <div class="rounded bg-secondary opacity-25 mb-2 w-50" style="height: 8px;"></div>
                                    <div class="rounded bg-secondary opacity-25 w-25" style="height: 6px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Badge 1 -->
                        <div class="bg-white rounded-pill shadow-sm py-2 px-3 position-absolute d-flex align-items-center" style="top: -20px; right: -10px; z-index: 1; animation: floating 6s ease-in-out infinite alternate;">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">Sistem Aman</span>
                        </div>

                        <!-- Floating Badge 2 -->
                        <div class="bg-white rounded-3 shadow py-2 px-3 position-absolute d-flex align-items-center gap-2" style="bottom: -20px; left: -20px; z-index: 3; animation: floating 4s ease-in-out infinite alternate-reverse;">
                            <div class="rounded d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background: rgba(0,97,255,0.1); color: var(--primary-color);">
                                <i class="bi bi-people-fill fs-5"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1;">Data Warga</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Otomatis Sinkron</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Animated Village Section -->
    <section class="py-5 bg-light overflow-hidden position-relative">
        <div class="container text-center mb-5 position-relative z-2" data-aos="fade-up">
            <span class="text-primary fw-bold text-uppercase tracking-wider">Ekosistem Digital</span>
            <h2 class="fw-bold mt-2 mb-3 display-6">Lingkungan RT Yang Hidup</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Bayangkan semua warga, rumah, dan aktivitas di RT Anda saling terhubung dalam satu sistem yang rapi dan aman.</p>
        </div>
        
        <div class="village-scene mx-auto position-relative" style="height: 250px; max-width: 800px; border-bottom: 4px solid var(--text-dark);">
            <!-- Sun -->
            <div class="position-absolute text-warning" style="top: 20px; right: 10%; animation: spin 10s linear infinite;">
                <i class="bi bi-brightness-high-fill" style="font-size: 3rem;"></i>
            </div>
            
            <!-- Clouds -->
            <div class="position-absolute text-secondary opacity-50" style="top: 30px; left: -100px; animation: slideRight 20s linear infinite;">
                <i class="bi bi-cloud-fill" style="font-size: 4rem;"></i>
            </div>
            <div class="position-absolute text-secondary opacity-25" style="top: 60px; left: -150px; animation: slideRight 25s linear infinite 5s;">
                <i class="bi bi-cloud-fill" style="font-size: 3rem;"></i>
            </div>

            <!-- Buildings/Houses -->
            <div class="position-absolute" style="bottom: -15px; left: 10%; z-index: 2;">
                <i class="bi bi-house-door-fill" style="font-size: 5rem; color: var(--primary-color);"></i>
            </div>
            <div class="position-absolute" style="bottom: -22px; left: 30%; z-index: 1;">
                <i class="bi bi-building-fill" style="font-size: 8rem; color: var(--secondary-color);"></i>
            </div>
            <div class="position-absolute" style="bottom: -10px; left: 60%; z-index: 2;">
                <i class="bi bi-shop" style="font-size: 4rem; color: #ff6b6b;"></i>
            </div>
            <div class="position-absolute" style="bottom: -15px; right: 15%; z-index: 1;">
                <i class="bi bi-house-fill" style="font-size: 6rem; color: var(--text-dark);"></i>
            </div>

            <!-- Trees -->
            <div class="position-absolute" style="bottom: -5px; left: 25%; z-index: 3;">
                <i class="bi bi-tree-fill text-success" style="font-size: 3rem;"></i>
            </div>
            <div class="position-absolute" style="bottom: -10px; right: 35%; z-index: 3;">
                <i class="bi bi-tree-fill text-success" style="font-size: 4rem;"></i>
            </div>

            <!-- Animated Person Walking -->
            <div class="position-absolute" style="bottom: -5px; left: -50px; z-index: 4; animation: slideRight 15s linear infinite;">
                <i class="bi bi-person-walking text-dark" style="font-size: 2rem;"></i>
            </div>
            <!-- Animated Bicycle -->
            <div class="position-absolute" style="bottom: -5px; right: -50px; z-index: 4; animation: slideLeft 12s linear infinite;">
                <i class="bi bi-bicycle text-primary" style="font-size: 2rem;"></i>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-5 my-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold text-uppercase tracking-wider">FITUR UNGGULAN</span>
                <h2 class="fw-bold mt-2 mb-3 display-6">Semua Yang Anda Butuhkan</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Kami mendesain fitur yang benar-benar mempermudah tugas pengurus RT sehari-hari.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-people-fill"></i></div>
                        <h4 class="fw-bold mb-3">Database Warga</h4>
                        <p class="text-muted">Kelola data KK, NIK, dan demografi warga secara digital. Pencarian super cepat dan data tersimpan aman di cloud.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-clipboard2-check-fill"></i></div>
                        <h4 class="fw-bold mb-3">Buku Tamu Digital</h4>
                        <p class="text-muted">Security dapat mencatat kunjungan tamu real-time, lengkap dengan foto wajah/KTP dan titik geolokasi GPS.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-envelope-paper-fill"></i></div>
                        <h4 class="fw-bold mb-3">Surat Pengantar</h4>
                        <p class="text-muted">Warga bisa mengajukan surat pengantar mandiri secara online dan RT tinggal melakukan approval.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-wallet2"></i></div>
                        <h4 class="fw-bold mb-3">Keuangan & Kas</h4>
                        <p class="text-muted">Catat pemasukan dan pengeluaran kas RT dengan transparan. Laporan bulanan dapat diunduh kapan saja.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-wifi-off"></i></div>
                        <h4 class="fw-bold mb-3">Offline-First (PWA)</h4>
                        <p class="text-muted">Tidak ada sinyal? Tidak masalah. Aplikasi tetap bisa digunakan dan akan sinkronisasi otomatis saat online.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-shield-lock-fill"></i></div>
                        <h4 class="fw-bold mb-3">Keamanan Data</h4>
                        <p class="text-muted">Sistem keamanan enkripsi standar industri memastikan privasi data warga Anda terlindungi dengan baik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="pricing-section py-5 my-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold text-uppercase tracking-wider">HARGA TRANSPARAN</span>
                <h2 class="fw-bold mt-2 mb-3 display-6">Pilih Paket Sesuai Kebutuhan</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Mulai digitalisasi RT Anda secara gratis, dan tingkatkan sesuai pertumbuhan lingkungan Anda.</p>
            </div>

            <div class="row g-4 justify-content-center align-items-center mt-4">
                <!-- Basic Plan -->
                <div class="col-lg-4 col-md-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="pricing-card p-5">
                        <h4 class="fw-bold text-muted mb-2">Paket Basic</h4>
                        <p class="text-muted mb-4">Cocok untuk RT berskala kecil.</p>
                        <div class="mb-4">
                            <span class="price-text">Rp 0</span>
                            <span class="text-muted">/selamanya</span>
                        </div>
                        <ul class="list-unstyled feature-list text-start mb-5">
                            <li><i class="bi bi-check-circle-fill text-success"></i> Maksimal 50 Kepala Keluarga</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Buku Tamu (Tanpa Foto/GPS)</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Manajemen Kas Sederhana</li>
                            <li class="opacity-50"><i class="bi bi-x-circle-fill text-danger"></i> Surat Pengantar Online</li>
                            <li class="opacity-50"><i class="bi bi-x-circle-fill text-danger"></i> Dukungan Prioritas</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-custom btn-outline-primary w-100 fw-bold bg-light">Mulai Gratis</a>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing-card pro p-5">
                        <div class="badge-popular">PALING DIMINATI</div>
                        <h4 class="fw-bold text-primary mb-2">Paket Pro</h4>
                        <p class="text-muted mb-4">Fitur lengkap untuk RT modern.</p>
                        <div class="mb-4">
                            <span class="price-text">Rp 99K</span>
                            <span class="text-muted">/bulan</span>
                        </div>
                        <ul class="list-unstyled feature-list text-start mb-5">
                            <li><i class="bi bi-check-circle-fill text-success"></i> <b>Tak Terbatas</b> Kepala Keluarga</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Buku Tamu (Dengan Foto & GPS)</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Laporan Keuangan Export Excel</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Pengajuan Surat Online Warga</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> WhatsApp Notifikasi (Segera)</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Customer Support 24/7</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-custom btn-primary-custom w-100">Berlangganan Pro</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 mb-5">
        <div class="container">
            <div class="p-5 text-center text-white rounded-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary-color) 0%, #00d2ff 100%);" data-aos="zoom-in">
                <!-- Decorative Circles -->
                <div class="position-absolute rounded-circle" style="width: 200px; height: 200px; background: rgba(255,255,255,0.1); top: -50px; right: -50px;"></div>
                <div class="position-absolute rounded-circle" style="width: 150px; height: 150px; background: rgba(255,255,255,0.1); bottom: -50px; left: -50px;"></div>
                
                <h2 class="fw-bold mb-3 position-relative z-1">Siap Mendigitalisasi RT Anda?</h2>
                <p class="lead mb-4 opacity-75 position-relative z-1">Bergabunglah dengan ratusan pengurus RT lainnya yang sudah menikmati kemudahan LaporPakRT.</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-custom text-primary px-5 fw-bold position-relative z-1">Daftar Sekarang</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="pt-5 pb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h5 class="fw-bold mb-2"><i class="bi bi-shield-fill-check text-primary"></i> LaporPakRT</h5>
                    <p class="text-muted small mb-0">Platform Digitalisasi Rukun Tetangga #1 di Indonesia.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} LaporPakRT. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            once: true, // Animasi hanya berjalan sekali
            offset: 50, // Trigger animasi 50px sebelum elemen terlihat
        });

        // Navbar blur on scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar-glass').style.background = 'rgba(255, 255, 255, 0.95)';
                document.querySelector('.navbar-glass').style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.05)';
            } else {
                document.querySelector('.navbar-glass').style.background = 'rgba(255, 255, 255, 0.85)';
                document.querySelector('.navbar-glass').style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>

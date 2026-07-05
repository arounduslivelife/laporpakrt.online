<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporPakRT - Pencatatan RT Lebih Mudah</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS & Icons -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- AOS CSS (Animasi Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0ea5e9;
            --primary-hover: #0284c7;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: white;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            letter-spacing: -0.02em;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--text-dark) !important;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            margin: 0 0.5rem;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.25);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.35);
        }
        
        .btn-outline {
            border: 2px solid #e2e8f0;
            background: white;
            color: var(--text-dark);
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            border-color: var(--text-dark);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            padding: 180px 0 100px;
            background: radial-gradient(circle at top right, rgba(14, 165, 233, 0.05) 0%, rgba(255,255,255,0) 60%);
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--text-dark);
        }
        
        .hero-title .highlight {
            color: var(--primary);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Hero Graphics Animation */
        .hero-graphics {
            position: relative;
            height: 400px;
        }

        .graphic-card {
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
            padding: 24px;
            position: absolute;
            transition: transform 0.5s ease;
            animation: float 6s ease-in-out infinite;
        }

        .card-main {
            width: 80%;
            top: 20px;
            right: 0;
            z-index: 2;
        }

        .card-sub {
            width: 60%;
            bottom: 40px;
            left: 0;
            z-index: 3;
            animation-delay: -3s;
            box-shadow: 0 20px 40px rgba(14, 165, 233, 0.1);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Feature Section */
        .section-header {
            max-width: 600px;
            margin: 0 auto 4rem auto;
            text-align: center;
        }

        .feature-card {
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: #e0f2fe;
            box-shadow: 0 20px 40px rgba(14, 165, 233, 0.08);
            transform: translateY(-8px);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: #f0f9ff;
            color: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            color: white;
            transform: scale(1.05) rotate(-5deg);
        }

        /* Pricing Section */
        .pricing-section {
            background-color: var(--bg-light);
            padding: 100px 0;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }

        .pricing-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 3rem;
            height: 100%;
            transition: all 0.3s ease;
        }

        .pricing-card.premium {
            border: 2px solid var(--primary);
            box-shadow: 0 20px 40px rgba(14, 165, 233, 0.15);
            position: relative;
            transform: scale(1.02);
        }

        .pricing-card:hover {
            transform: translateY(-10px);
        }
        
        .pricing-card.premium:hover {
            transform: scale(1.02) translateY(-10px);
        }

        .badge-premium {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 6px 20px;
            border-radius: 20px;
        }

        .pricing-list li {
            padding: 0.75rem 0;
            color: var(--text-dark);
            display: flex;
            align-items: flex-start;
        }
        
        .pricing-list i {
            margin-right: 12px;
            margin-top: 2px;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background: white;
            padding: 3rem 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-shield-check text-primary me-2"></i> LaporPakRT
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kemudahan">Kemudahan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#harga">Harga</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none fw-semibold">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-4 py-2">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-up" data-aos-duration="1000">
                    <div class="d-inline-flex align-items-center mb-3 px-3 py-1 rounded-pill bg-light border">
                        <span class="d-block rounded-circle bg-success me-2" style="width: 8px; height: 8px;"></span>
                        <span class="fw-semibold text-muted" style="font-size: 0.85rem;">Sistem Pencatatan RT 2.0</span>
                    </div>
                    
                    <h1 class="hero-title mb-4">
                        Pencatatan RT lebih mudah dengan <br>
                        <span class="highlight">LaporPakRT.online</span>
                    </h1>
                    
                    <p class="hero-subtitle mb-5">
                        Tinggalkan buku catatan manual. Kelola data warga, tamu, dan keuangan RT dalam satu aplikasi berbasis web yang bersih dan cepat.
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center">
                            Mulai Gunakan <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#harga" class="btn btn-outline d-inline-flex align-items-center justify-content-center text-decoration-none">
                            Lihat Harga
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                    <div class="hero-graphics d-none d-md-block">
                        <!-- Abstract Graphic Card 1 -->
                        <div class="graphic-card card-main">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Total Kas Bulan Ini</h6>
                                <i class="bi bi-wallet2 text-primary fs-4"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-1">Rp 4.500.000</h2>
                            <p class="text-success small fw-semibold"><i class="bi bi-arrow-up-short"></i> +12% dari bulan lalu</p>
                            
                            <div class="mt-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small text-muted">Target Iuran</span>
                                    <span class="small fw-bold">85%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Abstract Graphic Card 2 -->
                        <div class="graphic-card card-sub">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-upc-scan fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Tamu Terdaftar</h6>
                                    <small class="text-muted">Baru saja masuk</small>
                                </div>
                            </div>
                            <div class="bg-light rounded p-3 text-center border">
                                <span class="fw-bold">Ahmad Fauzi</span><br>
                                <span class="text-muted small">Mengunjungi Blok B/12</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kemudahan Section -->
    <section id="kemudahan" class="py-5 my-5">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="fw-bold mb-3">Kemudahan Yang Kami Berikan</h2>
                <p class="text-muted">Desain antarmuka yang tidak membingungkan, dirancang khusus agar pengurus RT dan Keamanan dapat langsung bekerja tanpa perlu buku panduan.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card px-4 py-4">
                        <div class="feature-icon"><i class="bi bi-people"></i></div>
                        <h5 class="fw-bold mb-3">Database Warga</h5>
                        <p class="text-muted small">Kelola data KK, NIK, dan anggota keluarga dengan rapi. Pencarian nama dan blok rumah warga sangat cepat.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card px-4 py-4">
                        <div class="feature-icon"><i class="bi bi-file-earmark-text"></i></div>
                        <h5 class="fw-bold mb-3">Surat Pengantar</h5>
                        <p class="text-muted small">Warga dapat meminta surat pengantar dan RT dapat menerbitkannya dalam format PDF secara otomatis.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card px-4 py-4">
                        <div class="feature-icon"><i class="bi bi-upc-scan"></i></div>
                        <h5 class="fw-bold mb-3">Tamu KTP & GPS</h5>
                        <p class="text-muted small">Satpam foto KTP tamu, AI otomatis mengisi Nama/NIK. Dilengkapi pencatatan waktu keluar-masuk dan koordinat GPS.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card px-4 py-4">
                        <div class="feature-icon"><i class="bi bi-wallet2"></i></div>
                        <h5 class="fw-bold mb-3">Manajemen Kas</h5>
                        <p class="text-muted small">Catat uang masuk/keluar bulanan secara transparan. Sistem menghitung saldo akhir otomatis tanpa repot.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Harga Section -->
    <section id="harga" class="pricing-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="fw-bold mb-3">Harga Sederhana</h2>
                <p class="text-muted">Tidak ada biaya tersembunyi. Gunakan gratis selama seminggu, lalu berlangganan jika aplikasi ini terbukti mempermudah tugas Anda.</p>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($plans ?? [] as $plan)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="pricing-card {{ $plan->price > 0 ? 'premium' : '' }}">
                        @if($plan->price > 0)
                        <div class="badge-premium">BERLANGGANAN</div>
                        @endif
                        <h4 class="fw-bold mb-1 {{ $plan->price > 0 ? 'text-primary' : '' }}">{{ $plan->name }}</h4>
                        <p class="text-muted mb-4 pb-2 border-bottom">Masa aktif {{ $plan->duration_days }} Hari.</p>
                        
                        <div class="mb-4">
                            <span class="display-5 fw-bold text-dark">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                            @if($plan->price > 0)
                            <small class="text-muted fw-semibold"> / {{ $plan->duration_days }} hari</small>
                            @endif
                        </div>
                        
                        <ul class="list-unstyled pricing-list mb-5">
                            @foreach($plan->features ?? [] as $feature)
                            <li><i class="bi bi-check-circle-fill {{ $plan->price > 0 ? 'text-primary' : 'text-success' }}"></i> {{ $feature }}</li>
                            @endforeach
                            @if($plan->price > 0)
                            <li><i class="bi bi-check-circle-fill text-primary"></i> Dukungan Bantuan Teknis</li>
                            <li><i class="bi bi-check-circle-fill text-primary"></i> Data Tidak Terhapus (Aman)</li>
                            @else
                            <li><i class="bi bi-check-circle-fill text-success"></i> Uji Coba Penuh</li>
                            @endif
                        </ul>
                        
                        <a href="{{ route('register') }}" class="btn {{ $plan->price > 0 ? 'btn-primary' : 'btn-outline' }} w-100 text-center text-decoration-none">
                            {{ $plan->price > 0 ? 'Perpanjang Akses' : 'Daftar Sekarang' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="300">
                <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Aturan Keamanan: Jika RT tidak memperpanjang masa aktif melebihi batas toleransi (+2 hari dari jatuh tempo), data akan <strong>dihapus permanen</strong> untuk perlindungan privasi.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-top">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-3 mb-md-0 text-center text-md-start">
                    <div class="fw-bold text-dark fs-5 mb-1"><i class="bi bi-shield-check text-primary me-1"></i> LaporPakRT</div>
                    <small class="text-muted">Pencatatan RT lebih mudah dan modern.</small>
                </div>
                <div class="text-center text-md-end text-muted small">
                    <div class="mb-2">
                        <span class="badge bg-light text-dark border fs-6">{{ number_format($visitorCount ?? 0) }} <i class="bi bi-eye text-primary ms-1"></i></span>
                    </div>
                    &copy; {{ date('Y') }} LaporPakRT.online<br>
                    All rights reserved.
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

        // Navbar Shadow on Scroll
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>

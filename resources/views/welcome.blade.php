<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporPakRT - SaaS Manajemen RT Modern</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #00d2ff 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .pricing-card {
            transition: transform 0.3s ease;
        }
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
</head>
<body oncontextmenu="return false;" onkeydown="if(event.keyCode==123) return false;">
    <!-- Anti-Inspect Script -->
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function(e) {
            if(e.keyCode == 123) { return false; } // F12
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) { return false; }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) { return false; }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) { return false; }
            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) { return false; }
        }
    </script>

    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="#">
                <i class="bi bi-shield-check"></i> LaporPakRT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-ms-auto navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#harga">Harga</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Masuk</a>
                        <a class="btn btn-primary" href="{{ route('register') }}">Daftar RT Baru</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Digitalisasi RT Anda Dalam Genggaman</h1>
            <p class="lead mb-5">Platform SaaS terlengkap untuk Ketua RT. Mendata warga dan tamu jadi jauh lebih mudah, cepat, dan aman dengan teknologi Offline-First.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg text-primary fw-bold rounded-pill px-5">Mulai Sekarang - Gratis!</a>
        </div>
    </section>

    <section id="fitur" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Fitur Unggulan</h2>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <i class="bi bi-people feature-icon"></i>
                    <h4>Data Warga Akurat</h4>
                    <p class="text-muted">Kelola data KK dan NIK warga RT Anda dengan sangat aman dan terpusat.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-book feature-icon"></i>
                    <h4>Buku Tamu Cerdas</h4>
                    <p class="text-muted">Catat kunjungan tamu secara real-time lengkap dengan bukti foto dan geolokasi.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-wifi-off feature-icon"></i>
                    <h4>Offline-First PWA</h4>
                    <p class="text-muted">Tetap bisa dipakai mendata warga dan tamu meski koneksi internet terputus.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="harga" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Pilihan Paket Berlangganan</h2>
            <div class="row justify-content-center g-4">
                <!-- Free Plan -->
                <div class="col-md-4">
                    <div class="card h-100 pricing-card border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <h3 class="text-muted">Paket Basic</h3>
                            <h1 class="display-4 fw-bold my-4">Rp 0</h1>
                            <ul class="list-unstyled mb-5 text-start">
                                <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i> Maksimal 50 KK</li>
                                <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i> Manajemen Tamu Standar</li>
                                <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i> Aplikasi PWA Offline</li>
                                <li class="mb-3 text-muted"><i class="bi bi-x-circle me-2"></i> Tanpa Geolokasi Presisi</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 fw-bold">Daftar Gratis</a>
                        </div>
                    </div>
                </div>
                <!-- Pro Plan -->
                <div class="col-md-4">
                    <div class="card h-100 pricing-card border-primary shadow">
                        <div class="card-body text-center p-5">
                            <span class="badge bg-primary rounded-pill mb-2 px-3 py-2">Paling Diminati</span>
                            <h3 class="text-primary">Paket Pro</h3>
                            <h1 class="display-4 fw-bold my-4">Rp 99K<span class="fs-5 text-muted">/bln</span></h1>
                            <ul class="list-unstyled mb-5 text-start">
                                <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i> Jumlah Warga <b>Tak Terbatas</b></li>
                                <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i> Upload Foto KTP & Wajah</li>
                                <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i> Geolokasi Kunjungan Tamu</li>
                                <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i> Dukungan Prioritas & Laporan Excel</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-primary w-100 fw-bold">Berlangganan Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} LaporPakRT. Sistem Digitalisasi Rukun Tetangga.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <title>{{ $title ?? 'Login - LaporPakRT Mobile' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @livewireStyles
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --text-main: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 100%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        
        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            padding: 40px 24px;
            width: 100%;
            max-width: 400px;
        }
        
        .brand-text {
            font-weight: 800;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--text-main), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-control-soft {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 16px;
            padding: 14px 20px;
            transition: all 0.2s;
        }
        .form-control-soft:focus {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        }
        
        .btn-primary-soft {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            padding: 14px 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.3);
        }
        .btn-primary-soft:active {
            transform: scale(0.96);
        }
    </style>
</head>
<body>
    
    <div class="container px-4 d-flex justify-content-center">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>

<?php

/**
 * LaporPakRT One-Click Deploy Script
 *
 * Cara pakai:
 * 1. Upload file ini ke /www/wwwroot/laporpakrt.online/deploy.php
 * 2. Akses via browser: https://laporpakrt.online/deploy.php?key=laporpakrt-deploy-2026
 * 3. Hapus file ini setelah deploy selesai
 */

// ============================================================================
// KONFIGURASI
// ============================================================================

$deployKey = $_GET['key'] ?? '';
$expectedKey = 'laporpakrt-deploy-2026'; // Ganti ini untuk keamanan

$domain = 'laporpakrt.online';
$webRoot = '/www/wwwroot/' . $domain;
$repoUrl = 'https://github.com/arounduslivelife/laporpakrt.online.git';

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'laporpakrtonline';
$dbUser = 'laporpakrtonline';
$dbPass = '4b353085af87f8';

$phpBin = '/usr/bin/php';
$composerBin = '/usr/local/bin/composer';

// ============================================================================
// KEAMANAN
// ============================================================================

if ($deployKey !== $expectedKey) {
    http_response_code(403);
    die('Akses ditolak. Gunakan parameter key yang benar.');
}

if (PHP_SAPI !== 'apache2handler' && PHP_SAPI !== 'fpm-fcgi' && PHP_SAPI !== 'cgi-fcgi') {
    echo "Mode CLI terdeteksi. Jalankan via browser untuk output real-time.\n";
}

// ============================================================================
// HELPER
// ============================================================================

function run(string $command, bool $print = true): int
{
    if ($print) {
        echo "<div class=\"cmd\">$ " . htmlspecialchars($command) . "</div>";
        flushOutput();
    }

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($command, $descriptors, $pipes);
    if (! is_resource($process)) {
        echo "<div class=\"error\">Gagal menjalankan perintah.</div>";
        return 1;
    }

    fclose($pipes[0]);

    while (! feof($pipes[1]) || ! feof($pipes[2])) {
        $stdout = feof($pipes[1]) ? '' : fgets($pipes[1]);
        $stderr = feof($pipes[2]) ? '' : fgets($pipes[2]);

        if ($stdout !== false && $stdout !== '') {
            echo '<pre class="out">' . htmlspecialchars($stdout) . '</pre>';
            flushOutput();
        }
        if ($stderr !== false && $stderr !== '') {
            echo '<pre class="err">' . htmlspecialchars($stderr) . '</pre>';
            flushOutput();
        }
    }

    fclose($pipes[1]);
    fclose($pipes[2]);

    return proc_close($process);
}

function flushOutput(): void
{
    if (ob_get_level() > 0) {
        ob_flush();
    }
    flush();
}

function section(string $title): void
{
    echo "<h2>" . htmlspecialchars($title) . "</h2>";
    flushOutput();
}

// ============================================================================
// OUTPUT HTML
// ============================================================================

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporPakRT Deploy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #e2e8f0; padding: 2rem; line-height: 1.6; }
        h1 { color: #38bdf8; border-bottom: 2px solid #334155; padding-bottom: 0.5rem; }
        h2 { color: #7dd3fc; margin-top: 2rem; font-size: 1.1rem; }
        .cmd { background: #1e293b; border-left: 4px solid #38bdf8; padding: 0.75rem 1rem; margin: 0.5rem 0; font-family: monospace; border-radius: 0 6px 6px 0; }
        pre { background: #1e293b; padding: 0.75rem 1rem; border-radius: 6px; overflow-x: auto; margin: 0.25rem 0; }
        .out { color: #e2e8f0; }
        .err { color: #f87171; }
        .success { background: #064e3b; color: #6ee7b7; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .error-box { background: #450a0a; color: #fca5a5; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .warning { background: #422006; color: #fde047; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        code { background: #334155; padding: 0.2rem 0.4rem; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>🚀 LaporPakRT Auto Deploy</h1>

<?php

$hasError = false;

// ============================================================================
// 1. PERSIAPAN DIREKTORI
// ============================================================================

section('1. Persiapan Direktori');

if (! is_dir($webRoot)) {
    run("mkdir -p " . escapeshellarg($webRoot));
}

run("cd " . escapeshellarg($webRoot) . " && pwd");

// ============================================================================
// 2. INSTALL SYSTEM DEPENDENCIES
// ============================================================================

section('2. Install System Dependencies');

run("apt-get update -y");
run("apt-get install -y git unzip curl nano wget");
run("apt-get install -y tesseract-ocr tesseract-ocr-ind");
run("tesseract --version");

// ============================================================================
// 3. INSTALL COMPOSER
// ============================================================================

section('3. Install Composer');

if (! file_exists($composerBin)) {
    run("curl -sS https://getcomposer.org/installer | php");
    run("mv composer.phar " . escapeshellarg($composerBin));
    run("chmod +x " . escapeshellarg($composerBin));
} else {
    echo "<div class=\"success\">Composer sudah terinstall.</div>";
}

run(escapeshellarg($composerBin) . " --version");

// ============================================================================
// 4. CLONE / PULL REPOSITORY
// ============================================================================

section('4. Pull Repository');

$gitDir = $webRoot . '/.git';

if (is_dir($gitDir)) {
    run("cd " . escapeshellarg($webRoot) . " && git pull origin main");
} else {
    run("cd " . escapeshellarg(dirname($webRoot)) . " && rm -rf " . escapeshellarg($webRoot));
    run("mkdir -p " . escapeshellarg($webRoot));
    run("cd " . escapeshellarg($webRoot) . " && git clone --depth 1 " . escapeshellarg($repoUrl) . " .");
}

// ============================================================================
// 5. SETUP ENVIRONMENT
// ============================================================================

section('5. Setup Environment');

$envPath = $webRoot . '/.env';
$envExamplePath = $webRoot . '/.env.example';

if (! file_exists($envPath) && file_exists($envExamplePath)) {
    run("cp " . escapeshellarg($envExamplePath) . " " . escapeshellarg($envPath));
}

if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);

    $replacements = [
        'APP_URL=http://localhost' => 'APP_URL=https://' . $domain,
        'APP_ENV=local' => 'APP_ENV=production',
        'APP_DEBUG=true' => 'APP_DEBUG=false',
        'DB_HOST=127.0.0.1' => 'DB_HOST=' . $dbHost,
        'DB_PORT=3306' => 'DB_PORT=' . $dbPort,
        'DB_DATABASE=laravel' => 'DB_DATABASE=' . $dbName,
        'DB_DATABASE=db_laporpakrt' => 'DB_DATABASE=' . $dbName,
        'DB_USERNAME=root' => 'DB_USERNAME=' . $dbUser,
        'DB_PASSWORD=' => 'DB_PASSWORD=' . $dbPass,
        'TESSERACT_PATH=' => 'TESSERACT_PATH=/usr/bin/tesseract',
        'TESSERACT_LANG=ind+eng' => 'TESSERACT_LANG=ind+eng',
    ];

    foreach ($replacements as $search => $replace) {
        if (str_contains($envContent, $search)) {
            $envContent = str_replace($search, $replace, $envContent);
        } else {
            // Append if not exists
            if (! str_contains($envContent, explode('=', $replace)[0] . '=')) {
                $envContent .= "\n" . $replace . "\n";
            }
        }
    }

    file_put_contents($envPath, $envContent);
    echo "<div class=\"success\">File .env berhasil dikonfigurasi.</div>";
}

// ============================================================================
// 6. INSTALL PHP DEPENDENCIES
// ============================================================================

section('6. Install PHP Dependencies');

$exitCode = run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($composerBin) . " install --no-dev --optimize-autoloader --no-interaction");
if ($exitCode !== 0) {
    $hasError = true;
    echo "<div class=\"error-box\">Composer install gagal. Periksa output di atas.</div>";
}

// ============================================================================
// 7. GENERATE APP KEY
// ============================================================================

section('7. Generate Application Key');

run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan key:generate --force");

// ============================================================================
// 8. DATABASE MIGRATION & SEEDER
// ============================================================================

section('8. Database Migration & Seeder');

$exitCode = run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan migrate --force");
if ($exitCode !== 0) {
    $hasError = true;
    echo "<div class=\"error-box\">Migration gagal. Pastikan database sudah dibuat di aaPanel dan kredensial benar.</div>";
}

run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan db:seed --force");

// ============================================================================
// 9. STORAGE LINK & PERMISSIONS
// ============================================================================

section('9. Storage Link & Permissions');

run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan storage:link");
run("cd " . escapeshellarg($webRoot) . " && chmod -R 775 storage bootstrap/cache");
run("cd " . escapeshellarg($webRoot) . " && chown -R www:www . 2>/dev/null || chown -R www-data:www-data .");

// ============================================================================
// 10. CACHE CONFIG
// ============================================================================

section('10. Cache Config & Routes');

run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan config:cache");
run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan route:cache");
run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan view:cache");

// ============================================================================
// SELESAI
// ============================================================================

section('Selesai');

if ($hasError) {
    echo "<div class=\"error-box\">Deploy selesai dengan beberapa error. Silakan periksa output di atas.</div>";
} else {
    echo "<div class=\"success\">✅ Deploy berhasil! Akses aplikasi di: <a href=\"https://$domain\" style=\"color:#6ee7b7\">https://$domain</a></div>";
}

echo "<div class=\"warning\">⚠️ <strong>Penting:</strong> Hapus file <code>deploy.php</code> ini setelah deploy selesai untuk keamanan.</div>";

?>
</body>
</html>

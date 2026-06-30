<?php

/**
 * LaporPakRT Deploy Script v2
 *
 * Fokus: deploy Laravel setelah system deps siap.
 * Jalankan dulu via SSH sebagai root untuk install system deps & composer.
 */

set_time_limit(600);
ini_set('max_execution_time', '600');
ini_set('memory_limit', '512M');
ini_set('display_errors', '1');
error_reporting(E_ALL);

// ============================================================================
// KONFIGURASI
// ============================================================================

$domain = 'laporpakrt.online';
$webRoot = '/www/wwwroot/' . $domain;
$repoUrl = 'https://github.com/arounduslivelife/laporpakrt.online.git';

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'laporpakrtonline';
$dbUser = 'laporpakrtonline';

// ============================================================================
// DETEKSI BINARY
// ============================================================================

function detectPhp(): string
{
    $candidates = [
        '/www/server/php/83/bin/php',
        '/www/server/php/82/bin/php',
        '/www/server/php/81/bin/php',
        '/www/server/php/80/bin/php',
        '/usr/bin/php83',
        '/usr/bin/php8.3',
        '/usr/bin/php',
    ];

    foreach ($candidates as $bin) {
        if (is_executable($bin)) {
            return $bin;
        }
    }

    return 'php';
}

function detectComposer(): string
{
    $candidates = [
        '/usr/local/bin/composer',
        '/usr/bin/composer',
        '/www/server/php/83/bin/composer',
        'composer',
    ];

    foreach ($candidates as $bin) {
        if ($bin === 'composer' || is_executable($bin)) {
            return $bin;
        }
    }

    return 'composer';
}

$phpBin = detectPhp();
$composerBin = detectComposer();

// ============================================================================
// HELPER
// ============================================================================

function run(string $command, bool $print = true): int
{
    if ($print) {
        echo "<div class=\"cmd\">$ " . htmlspecialchars($command) . "</div>";
        flushOutput();
    }

    $output = [];
    $exitCode = 0;

    exec($command . ' 2>&1', $output, $exitCode);

    foreach ($output as $line) {
        echo '<pre class="' . ($exitCode !== 0 ? 'err' : 'out') . '">' . htmlspecialchars($line) . '</pre>';
    }

    flushOutput();

    return $exitCode;
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

function commandExists(string $cmd): bool
{
    $which = str_starts_with(PHP_OS, 'WIN') ? 'where' : 'which';
    exec("$which " . escapeshellarg($cmd) . " 2>/dev/null", $output, $exitCode);
    return $exitCode === 0 && ! empty($output[0]);
}

function testConnection(array $config): bool
{
    try {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_TIMEOUT => 3,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        return true;
    } catch (Throwable $e) {
        return false;
    }
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
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #e2e8f0; padding: 2rem; line-height: 1.6; max-width: 900px; margin: 0 auto; }
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
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.75rem; margin-top: 0.25rem; background: #1e293b; border: 1px solid #475569; color: #e2e8f0; border-radius: 6px; }
        button { margin-top: 1.5rem; padding: 0.75rem 1.5rem; background: #38bdf8; color: #0f172a; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; }
        button:hover { background: #7dd3fc; }
        .check { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #334155; }
        .ok { color: #6ee7b7; }
        .fail { color: #f87171; }
        .info { color: #93c5fd; }
        form { background: #1e293b; padding: 1.5rem; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>🚀 LaporPakRT Deploy</h1>

<?php

$step = $_POST['step'] ?? 'form';

// ============================================================================
// FORM AWAL
// ============================================================================

if ($step !== 'deploy') {
    ?>
    <div class="warning">
        <strong>Sebelum menjalankan deploy:</strong>
        <ol>
            <li>Install system dependencies via SSH sebagai root:</li>
        </ol>
        <pre class="cmd">sudo apt update
sudo apt install -y git unzip curl nano wget tesseract-ocr tesseract-ocr-ind
sudo systemctl restart apache2
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer</pre>
        <ol start="2">
            <li>Buat database <code>laporpakrtonline</code> di aaPanel dengan user <code>laporpakrtonline</code></li>
            <li>Upload file <code>deploy.php</code> ke <code>/www/wwwroot/laporpakrt.online/deploy.php</code></li>
        </ol>
    </div>

    <h2>Prerequisite Check</h2>
    <div class="check"><span>PHP Binary</span><span class="<?php echo is_executable($phpBin) ? 'ok' : 'fail'; ?>"><?php echo htmlspecialchars($phpBin); ?></span></div>
    <div class="check"><span>Composer</span><span class="<?php echo commandExists('composer') ? 'ok' : 'fail'; ?>"><?php echo commandExists('composer') ? 'ok' : 'not found'; ?></span></div>
    <div class="check"><span>Git</span><span class="<?php echo commandExists('git') ? 'ok' : 'fail'; ?>"><?php echo commandExists('git') ? 'ok' : 'not found'; ?></span></div>
    <div class="check"><span>Tesseract OCR</span><span class="<?php echo commandExists('tesseract') ? 'ok' : 'fail'; ?>"><?php echo commandExists('tesseract') ? 'ok' : 'not found'; ?></span></div>

    <form method="post">
        <input type="hidden" name="step" value="deploy">

        <label>Database Password</label>
        <input type="password" name="db_password" placeholder="Masukkan password database laporpakrtonline" required>

        <label>Deploy Key</label>
        <input type="text" name="deploy_key" placeholder="laporpakrt-deploy-2026" required>

        <label>PHP Binary Path (auto-detected)</label>
        <input type="text" name="php_bin" value="<?php echo htmlspecialchars($phpBin); ?>">

        <label>Composer Binary Path (auto-detected)</label>
        <input type="text" name="composer_bin" value="<?php echo htmlspecialchars($composerBin); ?>">

        <button type="submit">Mulai Deploy</button>
    </form>
    </body>
    </html>
    <?php
    exit;
}

// ============================================================================
// VALIDASI
// ============================================================================

$expectedKey = 'laporpakrt-deploy-2026';
$deployKey = $_POST['deploy_key'] ?? '';
$dbPass = $_POST['db_password'] ?? '';
$phpBin = $_POST['php_bin'] ?? $phpBin;
$composerBin = $_POST['composer_bin'] ?? $composerBin;

if ($deployKey !== $expectedKey) {
    http_response_code(403);
    die('<div class="error-box">Deploy key salah.</div>');
}

if (empty($dbPass)) {
    die('<div class="error-box">Password database wajib diisi.</div>');
}

if (! is_executable($phpBin) && $phpBin !== 'php') {
    die('<div class="error-box">PHP binary tidak ditemukan: ' . htmlspecialchars($phpBin) . '</div>');
}

// ============================================================================
// DEPLOY
// ============================================================================

$hasError = false;

// 1. Persiapan Direktori
section('1. Persiapan Direktori');
if (! is_dir($webRoot)) {
    run("mkdir -p " . escapeshellarg($webRoot));
}
run("cd " . escapeshellarg($webRoot) . " && pwd");

// 2. Clone / Pull
section('2. Clone / Pull Repository');
$gitDir = $webRoot . '/.git';
if (is_dir($gitDir)) {
    run("cd " . escapeshellarg($webRoot) . " && git pull origin main");
} else {
    run("rm -rf " . escapeshellarg($webRoot) . "/* " . escapeshellarg($webRoot) . "/.* 2>/dev/null; true");
    run("cd " . escapeshellarg($webRoot) . " && git clone --depth 1 " . escapeshellarg($repoUrl) . " .");
}

// 3. Setup .env
section('3. Setup Environment');
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
        'DB_DATABASE=laporpakrtonline' => 'DB_DATABASE=' . $dbName,
        'DB_USERNAME=root' => 'DB_USERNAME=' . $dbUser,
    ];

    foreach ($replacements as $search => $replace) {
        if (str_contains($envContent, $search)) {
            $envContent = str_replace($search, $replace, $envContent);
        } else {
            $key = explode('=', $replace)[0];
            if (! str_contains($envContent, $key . '=')) {
                $envContent .= "\n" . $replace . "\n";
            }
        }
    }

    // Replace password line reliably
    $envContent = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD=' . $dbPass, $envContent);
    if (! str_contains($envContent, 'DB_PASSWORD=')) {
        $envContent .= "\nDB_PASSWORD=" . $dbPass . "\n";
    }

    // Tesseract config
    if (! str_contains($envContent, 'TESSERACT_PATH=')) {
        $envContent .= "\nTESSERACT_PATH=/usr/bin/tesseract\n";
    }
    if (! str_contains($envContent, 'TESSERACT_LANG=')) {
        $envContent .= "TESSERACT_LANG=ind+eng\n";
    }

    file_put_contents($envPath, $envContent);
    echo "<div class=\"success\">File .env berhasil dikonfigurasi.</div>";
}

// 4. Composer Install
section('4. Install PHP Dependencies');
$exitCode = run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($composerBin) . " install --no-dev --optimize-autoloader --no-interaction");
if ($exitCode !== 0) {
    $hasError = true;
    echo "<div class=";
    echo "error-box\">Composer install gagal. Coba jalankan manual via SSH.</div>";
}

// 5. Generate Key
section('5. Generate Application Key');
run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan key:generate --force");

// 6. Test DB Connection
section('6. Test Koneksi Database');
if (testConnection(['host' => $dbHost, 'port' => $dbPort, 'database' => $dbName, 'username' => $dbUser, 'password' => $dbPass])) {
    echo "<div class=\"success\">Koneksi database berhasil.</div>";
} else {
    $hasError = true;
    echo "<div class=\"error-box\">Koneksi database gagal. Periksa password dan pastikan database sudah dibuat.</div>";
}

// 7. Migrate & Seed
section('7. Database Migration & Seeder');
$exitCode = run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan migrate --force");
if ($exitCode !== 0) {
    $hasError = true;
}
run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan db:seed --force");

// 8. Storage Link & Permissions
section('8. Storage Link & Permissions');
run("cd " . escapeshellarg($webRoot) . " && " . escapeshellarg($phpBin) . " artisan storage:link");
run("cd " . escapeshellarg($webRoot) . " && chmod -R 775 storage bootstrap/cache");
run("cd " . escapeshellarg($webRoot) . " && chown -R www:www . 2>/dev/null || chown -R www-data:www-data . 2>/dev/null || true");

// 9. Cache
section('9. Cache Config & Routes');
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

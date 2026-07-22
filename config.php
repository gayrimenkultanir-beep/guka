<?php
/*
  ÖNEMLİ: Yüklemeden önce aşağıdaki şifreyi mutlaka değiştirin.
  Kolay tahmin edilemeyecek, en az 12 karakterli bir şifre seçin.
*/
define('ADMIN_PASSWORD', 'BURAYA-GUCLU-BIR-SIFRE-YAZIN-2026');

define('DATA_FILE', __DIR__ . '/../data/duyurular.json');
define('VALID_TAGS', ['genelge', 'mevzuat', 'kampanya']);

session_start();

function is_logged_in(): bool {
    return isset($_SESSION['sbm_auth']) && $_SESSION['sbm_auth'] === true;
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function read_announcements(): array {
    if (!file_exists(DATA_FILE)) {
        return [];
    }
    $raw = file_get_contents(DATA_FILE);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function write_announcements(array $items): bool {
    $fp = fopen(DATA_FILE, 'c+');
    if (!$fp) return false;
    if (!flock($fp, LOCK_EX)) { fclose($fp); return false; }
    ftruncate($fp, 0);
    rewind($fp);
    $json = json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    fwrite($fp, $json);
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf'];
}

function csrf_check(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

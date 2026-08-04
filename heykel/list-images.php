<?php
/* ============================================================
   list-images.php
   ------------------------------------------------------------
   assets/eserler/<cat>/ klasöründeki görsel dosyalarını okuyup
   JSON dizi olarak döndürür. Bu sayede site, bir kategoriye yeni
   görsel eklediğinizde kodda hiçbir değişiklik yapmanıza gerek
   kalmadan o görseli otomatik olarak sitede gösterir.

   Kullanım: list-images.php?cat=ataturk
   Yanıt   : ["01.webp","02.webp","gençlik-parkı.webp", ...]
============================================================ */

header('Content-Type: application/json; charset=utf-8');

$cat = isset($_GET['cat']) ? $_GET['cat'] : '';

/* Güvenlik: sadece küçük harf, rakam ve tire içeren klasör adlarına
   izin ver (path traversal / ../ girişimlerini engeller) */
if (!preg_match('/^[a-z0-9\-]+$/', $cat)) {
    echo json_encode([]);
    exit;
}

$baseDir = realpath(__DIR__ . '/assets/eserler');
$dir     = $baseDir !== false ? realpath($baseDir . '/' . $cat) : false;

/* İstenen klasörün gerçekten assets/eserler altında olduğunu doğrula */
if ($dir === false || $baseDir === false || strpos($dir, $baseDir) !== 0 || !is_dir($dir)) {
    echo json_encode([]);
    exit;
}

$allowedExt = ['webp', 'jpg', 'jpeg', 'png', 'gif'];
$files = [];

foreach (scandir($dir) as $item) {
    if ($item === '.' || $item === '..') continue;
    if ($item === 'aciklamalar.json') continue; /* açıklama dosyası, görsel değil */
    $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
    if (in_array($ext, $allowedExt, true)) {
        $files[] = $item;
    }
}

natsort($files); /* 01,02...10 gibi dosyaları doğru sırayla listeler */
echo json_encode(array_values($files));

<?php
require __DIR__ . '/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered = $_POST['password'] ?? '';
    if (hash_equals(ADMIN_PASSWORD, $entered)) {
        $_SESSION['sbm_auth'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Şifre hatalı.';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Giriş | Duyuru Paneli</title>
<style>
  body{font-family:sans-serif; background:#0F2440; min-height:100vh; margin:0; display:flex; align-items:center; justify-content:center;}
  form{background:#fff; padding:32px 30px; border-radius:12px; width:280px;}
  h1{font-size:18px; margin:0 0 18px;}
  input{width:100%; padding:10px; margin-bottom:14px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box;}
  button{width:100%; padding:11px; background:#0F2440; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;}
  .err{color:#A8432C; font-size:13px; margin-bottom:12px;}
</style>
</head>
<body>
<form method="post">
  <h1>Duyuru Paneli Girişi</h1>
  <?php if ($error): ?><p class="err"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
  <input type="password" name="password" placeholder="Şifre" autofocus required>
  <button type="submit">Giriş yap</button>
</form>
</body>
</html>

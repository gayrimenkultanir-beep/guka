<?php
require __DIR__ . '/config.php';
require_login();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $items = read_announcements();

    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $date  = trim($_POST['date'] ?? '');
        $tag   = trim($_POST['tag'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $body  = trim($_POST['body'] ?? '');
        $link  = trim($_POST['link'] ?? '');

        if ($date !== '' && $title !== '' && in_array($tag, VALID_TAGS, true)) {
            array_unshift($items, [
                'date'  => $date,
                'tag'   => $tag,
                'title' => $title,
                'body'  => $body,
                'link'  => $link !== '' ? $link : '#',
            ]);
            write_announcements($items);
            $message = 'Duyuru eklendi.';
        } else {
            $message = 'Tarih, başlık ve kategori zorunludur.';
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $idx = (int)($_POST['index'] ?? -1);
        if (isset($items[$idx])) {
            array_splice($items, $idx, 1);
            write_announcements($items);
            $message = 'Duyuru silindi.';
        }
    }
}

$items = read_announcements();
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Duyuru Paneli | Hayat Sigorta</title>
<style>
  *{box-sizing:border-box;}
  body{font-family:sans-serif; background:#F3EFE6; margin:0; color:#171A1D;}
  header{background:#0F2440; color:#fff; padding:18px 26px; display:flex; justify-content:space-between; align-items:center;}
  header a{color:#C9C2AE; font-size:13px; text-decoration:none;}
  main{max-width:780px; margin:0 auto; padding:30px 20px 60px;}
  h1{font-size:20px; margin:0 0 4px;}
  .msg{background:#E7EFE6; color:#2E5C48; padding:10px 14px; border-radius:8px; font-size:14px; margin-bottom:20px;}
  form.add{background:#fff; border:1px solid #DAD2BE; border-radius:12px; padding:22px; margin-bottom:32px;}
  form.add label{display:block; font-size:12.5px; color:#5C6570; margin:12px 0 4px;}
  form.add input, form.add select, form.add textarea{
    width:100%; padding:9px 10px; border:1px solid #ccc; border-radius:6px; font-size:14px; font-family:inherit;
  }
  form.add textarea{resize:vertical; min-height:60px;}
  .add-btn{margin-top:16px; background:#B9873C; color:#241800; border:none; padding:11px 18px; border-radius:6px; font-weight:600; cursor:pointer;}
  .item{background:#fff; border:1px solid #DAD2BE; border-radius:10px; padding:16px 18px; margin-bottom:12px; display:flex; justify-content:space-between; gap:14px;}
  .item .meta{font-size:12px; color:#5C6570; margin-bottom:4px;}
  .item h3{font-size:15px; margin:0 0 4px;}
  .item p{font-size:13px; color:#5C6570; margin:0;}
  .del-btn{background:none; border:1px solid #A8432C; color:#A8432C; border-radius:6px; padding:6px 12px; font-size:12.5px; cursor:pointer; align-self:flex-start; flex:none;}
  .empty{color:#5C6570; font-size:14px;}
</style>
</head>
<body>
<header>
  <div>Duyuru Paneli</div>
  <a href="logout.php">Çıkış yap</a>
</header>
<main>
  <h1>Sektör duyuruları</h1>
  <p style="color:#5C6570; font-size:13.5px; margin-bottom:20px;">Buradan eklediğiniz/sildiğiniz duyurular siteye anında yansır.</p>

  <?php if ($message): ?><div class="msg"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

  <form class="add" method="post">
    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">
    <input type="hidden" name="action" value="add">

    <label>Tarih (örn. 22.07.2026)</label>
    <input type="text" name="date" required>

    <label>Kategori</label>
    <select name="tag">
      <option value="genelge">Genelge</option>
      <option value="mevzuat">Mevzuat</option>
      <option value="kampanya">Kampanya</option>
    </select>

    <label>Başlık</label>
    <input type="text" name="title" required>

    <label>Kısa açıklama</label>
    <textarea name="body"></textarea>

    <label>Kaynak linki (opsiyonel)</label>
    <input type="url" name="link" placeholder="https://www.seddk.gov.tr/...">

    <button class="add-btn" type="submit">Duyuru ekle</button>
  </form>

  <?php if (empty($items)): ?>
    <p class="empty">Henüz duyuru eklenmemiş.</p>
  <?php endif; ?>

  <?php foreach ($items as $i => $it): ?>
    <div class="item">
      <div>
        <div class="meta"><?php echo htmlspecialchars($it['date']); ?> · <?php echo htmlspecialchars($it['tag']); ?></div>
        <h3><?php echo htmlspecialchars($it['title']); ?></h3>
        <p><?php echo htmlspecialchars($it['body']); ?></p>
      </div>
      <form method="post" onsubmit="return confirm('Bu duyuru silinsin mi?');">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="index" value="<?php echo $i; ?>">
        <button class="del-btn" type="submit">Sil</button>
      </form>
    </div>
  <?php endforeach; ?>
</main>
</body>
</html>

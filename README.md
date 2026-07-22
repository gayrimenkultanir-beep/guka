# Sigorta Bilgi Merkezi — Kurulum

## Neden tam otomatik "canlı çekme" yapmadık
SEDDK'nin sitesi robots.txt ile otomatik erişimi (bot/scraper) engelliyor. Bu nedenle
sunucudan otomatik veri kazıma (scraping) kurmadık — hem teknik olarak sitede
kalıcı çalışmaz hem de kaynağın kurallarına aykırı olur. Onun yerine siz (veya
ekibiniz) kaynağı görünce 30 saniyede panelden ekliyorsunuz; sayfa anında güncelleniyor.

## Klasör yapısı
```
/                       -> index.html (asıl sayfa)
/data/duyurular.json    -> duyuru verisi (panel tarafından güncellenir)
/admin/                 -> şifreli yönetim paneli
```

## Kurulum adımları
1. Bu klasördeki tüm dosyaları (index.html, /data, /admin) cPanel dosya
   yöneticisi veya FTP ile sitenizin bir alt klasörüne yükleyin
   (örn. `bilgi-merkezi/`), ya da ayrı bir alan adı/alt alan adına.
2. **`/admin/config.php` içindeki `ADMIN_PASSWORD` değerini mutlaka değiştirin.**
   En az 12 karakter, tahmin edilemeyecek bir şifre kullanın.
3. `/data/duyurular.json` dosyasının PHP tarafından yazılabilir olduğundan
   emin olun (izin: 664 veya 666 — hosting'e göre değişir; cPanel dosya
   yöneticisinden "Permissions" ile ayarlanabilir).
4. `https://siteniz.com/bilgi-merkezi/admin/login.php` adresine gidip
   belirlediğiniz şifreyle giriş yapın.
5. İsteğe bağlı ek güvenlik: cPanel'de bu klasöre "Directory Privacy" /
   "Password Protect Directories" özelliğiyle ikinci bir şifre katmanı
   daha ekleyebilirsiniz.

## Kullanım
- `seddk.gov.tr/tr/duyurular` gibi kaynaklardan yeni bir genelge/duyuru
  gördüğünüzde panelden "Duyuru ekle" formunu doldurup kaydedin.
- Eski/geçersiz duyuruları "Sil" ile kaldırabilirsiniz.
- Sayfa her ziyarette `data/duyurular.json` dosyasını otomatik okur —
  ayrıca bir işlem yapmanıza gerek yoktur.

## Oranlar (BSMV, cayma süreleri vb.) bölümü
Bu veriler yılda birkaç kez, Cumhurbaşkanlığı kararları veya kanun
değişiklikleriyle güncellenir; sık değişmediği için şimdilik
`index.html` içinde doğrudan metin olarak tutuluyor. Değiştiğinde
`index.html` içindeki "Oranlar ve Genel Çerçeve" bölümünü elle
güncellemeniz yeterli. İsterseniz bunu da aynı panel mantığıyla
düzenlenebilir hale getirebiliriz.

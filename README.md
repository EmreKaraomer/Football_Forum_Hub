# ⚽ Futbol Forum Sayfası

> **Web Tabanlı Programlama Dersi** — PHP & MySQL CRUD Projesi

---

## 🎬 Tanıtım Videosu

📹 **Video Linki:** (https://youtu.be/mf-QHCvqVho)

---

## 📋 Proje Hakkında

Bu proje, futbolseverlerin tartışma başlıkları açıp yorum yapabileceği basit bir forum sayfasıdır. PHP ve MySQL kullanılarak geliştirilmiş olup CRUD (Create, Read, Update, Delete) işlemlerinin tamamını kapsamaktadır.

---

## 🛠 Kullanılan Teknolojiler

| Katman       | Teknoloji                     |
|--------------|-------------------------------|
| **Backend**  | Vanilla PHP (Framework yok)   |
| **Veritabanı** | MySQL / MariaDB (PDO ile)   |
| **Frontend** | HTML5 + Bootstrap 5           |
| **İkonlar**  | Bootstrap Icons               |

---

## ✅ Özellikler

- 🔐 **Kullanıcı Kaydı ve Girişi** — `password_hash()` ile güvenli şifreleme, PHP `session` ile oturum yönetimi
- 📝 **Başlık Oluşturma** (Create) — Giriş yapmış kullanıcılar yeni başlık açabilir
- 📋 **Başlık Listeleme** (Read) — Tüm başlıklar ana sayfada tarih sırasıyla listelenir; arama ve kategori filtresi mevcuttur
- ✏️ **Başlık Düzenleme** (Update) — Yalnızca kendi başlığını düzenleyebilme
- 🗑️ **Başlık Silme** (Delete) — Yalnızca kendi başlığını silebilme, onay ekranıyla birlikte

---

## 📁 Dosya Yapısı

```
football_forum/
├── config.php        # Veritabanı bağlantısı ve yardımcı fonksiyonlar
├── header.php        # Ortak navbar ve HTML açılışı
├── footer.php        # Ortak footer ve Bootstrap JS
├── index.php         # Ana sayfa – başlık listesi (READ)
├── register.php      # Kullanıcı kaydı
├── login.php         # Kullanıcı girişi
├── logout.php        # Oturum kapatma
├── create.php        # Yeni başlık oluştur (CREATE)
├── edit.php          # Başlık düzenle (UPDATE)
├── delete.php        # Başlık sil (DELETE)
└── database.sql      # Veritabanı ve tablo oluşturma scripti
```

---

## 🚀 Kurulum

### Yerel Ortamda (XAMPP / WAMP / MAMP)

1. Projeyi `htdocs` (veya `www`) klasörüne kopyalayın:
   ```
   C:\xampp\htdocs\football_forum\
   ```

2. **phpMyAdmin** üzerinden yeni bir veritabanı oluşturun ya da `database.sql` dosyasını içe aktarın:
   ```sql
   -- phpMyAdmin > Import > database.sql dosyasını seçin
   ```

3. `config.php` dosyasını düzenleyin:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'football_forum');
   define('DB_USER', 'root');
   define('DB_PASS', '');           // XAMPP için genellikle boş
   ```

4. Tarayıcıda açın:
   ```
   http://localhost/football_forum/
   ```

---

### Canlı Sunucuya (Hosting) Yükleme

1. Dosyaları FTP/cPanel File Manager ile `public_html` klasörüne yükleyin.
2. Hosting kontrol panelinizden MySQL veritabanı ve kullanıcı oluşturun.
3. `database.sql` dosyasını **phpMyAdmin → Import** ile çalıştırın.
4. `config.php` içindeki sabitleri hosting bilgilerinizle güncelleyin:
   ```php

---

## 🔒 Güvenlik Önlemleri

| Önlem | Açıklama |
|-------|----------|
| **Şifre Hash** | `password_hash()` + `password_verify()` ile BCrypt |
| **SQL Injection** | PDO Prepared Statements kullanıldı |
| **XSS Koruması** | Tüm çıktılar `htmlspecialchars()` ile kaçışlandı |
| **Yetki Kontrolü** | Düzenleme/silme işlemlerinde `user_id` karşılaştırması |
| **Session Fixation** | Giriş sonrası `session_regenerate_id(true)` çağrıldı |

---

## 📖 Gereksinimler

- PHP **8.0** veya üzeri
- MySQL **5.7** / MariaDB **10.3** veya üzeri
- PDO ve PDO_MySQL PHP eklentileri etkin olmalı

---

## 📝 Notlar

- Proje herhangi bir PHP framework'ü veya harici kütüphane kullanmamaktadır.
- `.htaccess` dosyası kullanılmamıştır.
- Bootstrap 5 CDN üzerinden yüklendiği için internet bağlantısı gerektirir.

---

**Hazırlayan: Emre Karaömer**

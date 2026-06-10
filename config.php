<?php


define('DB_HOST', 'localhost');   
define('DB_NAME', 'football_forum'); 
define('DB_USER', 'root');       
define('DB_PASS', '');           
define('DB_CHARSET', 'utf8mb4');

// Uygulama genel ayarlari
define('SITE_NAME', 'Futbol Forum');
define('SITE_URL', '');   

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PDO Baglantisi
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname=' . DB_NAME
             . ';charset=' . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
         
            die('<div style="font-family:sans-serif;padding:2rem;color:#721c24;background:#f8d7da;border:1px solid #f5c6cb;border-radius:8px;">
                    <strong>Veritabani baglantisi kurulamadi.</strong> Lutfen config.php dosyasini kontrol edin.
                 </div>');
        }
    }

    return $pdo;
}

// Yardimci fonksiyon: XSS onleme icin HTML kacis
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Yardimci fonksiyon: Giris yapilmis mi kontrol et
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// Yardimci fonksiyon: Giris yapilmamissa login'e yon lendir
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

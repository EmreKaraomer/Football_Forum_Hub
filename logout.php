<?php
// logout.php - Oturum Kapatma
require_once 'config.php';

// Tum oturum verilerini temizle
$_SESSION = [];

// Oturum cerezini sil
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

// Yeni oturum baslat (flash mesaji icin)
session_start();
$_SESSION['flash_success'] = 'Başarıyla çıkış yaptınız. Görüşmek üzere!';

header('Location: index.php');
exit;

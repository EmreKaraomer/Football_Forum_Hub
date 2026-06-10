<?php
// delete.php - Baslik Sil (DELETE)
require_once 'config.php';
requireLogin();

$id  = (int)($_GET['id'] ?? 0);
$pdo = getDB();

// Baslik var mi ve bu kullanicinin mi?
$stmt = $pdo->prepare('SELECT id, user_id, title FROM posts WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    $_SESSION['flash_error'] = 'Başlık bulunamadı.';
    header('Location: index.php');
    exit;
}

if ((int)$post['user_id'] !== (int)$_SESSION['user_id']) {
    $_SESSION['flash_error'] = 'Bu başlığı silme yetkiniz yok.';
    header('Location: index.php');
    exit;
}

// Onay sayfasi - POST ile geldiyse sil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, (int)$_SESSION['user_id']]);

    $_SESSION['flash_success'] = 'Başlık başarıyla silindi.';
    header('Location: index.php');
    exit;
}

// GET ile geldiyse onay ekrani goster
$pageTitle = 'Başlığı Sil';
require_once 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1 class="text-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i>Başlığı Sil</h1>
        <p class="mb-0">Bu işlem geri alınamaz, lütfen emin olun.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card form-card border-danger border-2">
                <div class="card-header bg-danger bg-opacity-10 border-bottom border-danger border-opacity-25 py-3">
                    <h5 class="mb-0 text-danger">
                        <i class="bi bi-trash3-fill me-2"></i>Silme Onayı
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-2">Şu başlığı silmek üzeresiniz:</p>
                    <div class="bg-light rounded p-3 mb-4">
                        <strong>"<?= e($post['title']) ?>"</strong>
                    </div>
                    <p class="text-danger small">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Bu işlem geri alınamaz. Başlık kalıcı olarak silinecektir.
                    </p>

                    <form method="post" class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-danger btn-lg flex-grow-1">
                            <i class="bi bi-trash3-fill me-1"></i> Evet, Sil
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary btn-lg flex-grow-1">
                            <i class="bi bi-x-circle me-1"></i> İptal
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<?php
// edit.php - Baslik Duzenle (UPDATE)
require_once 'config.php';
requireLogin();

$id  = (int)($_GET['id'] ?? 0);
$pdo = getDB();

// Baslik var mi ve bu kullanicinin mi?
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    $_SESSION['flash_error'] = 'Başlık bulunamadı.';
    header('Location: index.php');
    exit;
}

if ((int)$post['user_id'] !== (int)$_SESSION['user_id']) {
    $_SESSION['flash_error'] = 'Bu başlığı düzenleme yetkiniz yok.';
    header('Location: index.php');
    exit;
}

$availableCategories = [
    'Genel', 'Süper Lig', 'Şampiyonlar Ligi', 'Milli Takım',
    'Transfer', 'Teknik & Taktik', 'Diğer'
];

$errors  = [];
$title   = $post['title'];
$content = $post['content'];
$category= $post['category'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']    ?? '');
    $content  = trim($_POST['content']  ?? '');
    $category = trim($_POST['category'] ?? '');

    if (strlen($title) < 5) {
        $errors[] = 'Başlık en az 5 karakter olmalıdır.';
    }
    if (strlen($content) < 10) {
        $errors[] = 'İçerik en az 10 karakter olmalıdır.';
    }
    if (!in_array($category, $availableCategories, true)) {
        $errors[] = 'Geçersiz kategori seçimi.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'UPDATE posts SET title = ?, content = ?, category = ? WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$title, $content, $category, $id, (int)$_SESSION['user_id']]);

        $_SESSION['flash_success'] = 'Başlık başarıyla güncellendi.';
        header('Location: index.php');
        exit;
    }
}

$pageTitle = 'Başlığı Düzenle';
require_once 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><i class="bi bi-pencil-square me-2"></i>Başlığı Düzenle</h1>
        <p class="mb-0">Başlığınızda değişiklik yapın.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <a href="index.php" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-1"></i>Geri Dön
            </a>

            <div class="card form-card p-4">
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= e($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Orijinal olusturma tarihi bilgisi -->
                    <div class="alert alert-light border mb-4 py-2 px-3" style="font-size:.88rem;">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        <span class="text-muted">
                            Oluşturulma: <strong><?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></strong>
                        </span>
                    </div>

                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Başlık</label>
                            <input type="text" id="title" name="title"
                                   class="form-control form-control-lg"
                                   value="<?= e($title) ?>"
                                   maxlength="255"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold">Kategori</label>
                            <select id="category" name="category" class="form-select" required>
                                <?php foreach ($availableCategories as $cat): ?>
                                    <option value="<?= e($cat) ?>"
                                        <?= ($category === $cat) ? 'selected' : '' ?>>
                                        <?= e($cat) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">İçerik</label>
                            <textarea id="content" name="content"
                                      class="form-control"
                                      rows="7"
                                      required><?= e($content) ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-ff-primary btn-lg px-5">
                                <i class="bi bi-check-circle-fill me-1"></i> Güncelle
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary btn-lg">İptal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

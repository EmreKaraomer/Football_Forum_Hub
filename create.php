<?php
// create.php - Yeni Baslik Olustur (CREATE)
require_once 'config.php';
requireLogin(); // Giris yapilmamissa login'e yonlendir

$errors   = [];
$title    = '';
$content  = '';
$category = '';

$availableCategories = [
    'Genel', 'Süper Lig', 'Şampiyonlar Ligi', 'Milli Takım',
    'Transfer', 'Teknik & Taktik', 'Diğer'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']    ?? '');
    $content  = trim($_POST['content']  ?? '');
    $category = trim($_POST['category'] ?? '');

    // Dogrulama
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
        $pdo  = getDB();
        $stmt = $pdo->prepare(
            'INSERT INTO posts (user_id, title, content, category) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([(int)$_SESSION['user_id'], $title, $content, $category]);

        $_SESSION['flash_success'] = 'Başlığınız başarıyla oluşturuldu!';
        header('Location: index.php');
        exit;
    }
}

$pageTitle = 'Yeni Başlık';
require_once 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><i class="bi bi-plus-circle-fill me-2"></i>Yeni Başlık Aç</h1>
        <p class="mb-0">Forumda yeni bir tartışma başlatın.</p>
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

                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Başlık</label>
                            <input type="text" id="title" name="title"
                                   class="form-control form-control-lg"
                                   value="<?= e($title) ?>"
                                   placeholder="Tartışmak istediğiniz konuyu yazın..."
                                   maxlength="255"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold">Kategori</label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="">-- Kategori Seçin --</option>
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
                                      placeholder="Düşüncelerinizi, analizlerinizi veya sorularınızı buraya yazın..."
                                      required><?= e($content) ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-ff-primary btn-lg px-5">
                                <i class="bi bi-send-fill me-1"></i> Başlığı Gönder
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

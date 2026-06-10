<?php

require_once 'config.php';


$search   = trim($_GET['search']   ?? '');
$category = trim($_GET['category'] ?? '');

$pdo = getDB();

$catStmt = $pdo->query('SELECT DISTINCT category FROM posts ORDER BY category ASC');
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

$sql    = 'SELECT p.*, u.username
           FROM posts p
           JOIN users u ON p.user_id = u.id
           WHERE 1=1';
$params = [];

if ($search !== '') {
    $sql     .= ' AND (p.title LIKE ? OR p.content LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}
if ($category !== '') {
    $sql     .= ' AND p.category = ?';
    $params[] = $category;
}

$sql .= ' ORDER BY p.created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

$pageTitle = 'Forum Ana Sayfa';
require_once 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-1"><i class="bi bi-chat-square-dots-fill me-2"></i>Futbol Forumu</h1>
                <p class="mb-0 opacity-75">Takımları, maçları ve transferleri tartış!</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <?php if (isLoggedIn()): ?>
                    <a href="create.php" class="btn btn-warning fw-semibold">
                        <i class="bi bi-plus-lg me-1"></i> Yeni Başlık Aç
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Giriş Yap
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">

  
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Başlık veya İçerik Ara</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Ara..." value="<?= e($search) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Kategoriye Göre Filtrele</label>
                    <select name="category" class="form-select">
                        <option value="">Tüm Kategoriler</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat) ?>"
                                <?= ($category === $cat) ? 'selected' : '' ?>>
                                <?= e($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-ff-primary">
                        <i class="bi bi-funnel me-1"></i>Filtrele
                    </button>
                </div>
                <?php if ($search !== '' || $category !== ''): ?>
                <div class="col-12">
                    <a href="index.php" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Filtreyi Temizle
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>


    <p class="text-muted small mb-3">
        <i class="bi bi-list-ul me-1"></i>
        <strong><?= count($posts) ?></strong> başlık bulundu.
    </p>

    <?php if (empty($posts)): ?>
        <div class="text-center py-5">
            <i class="bi bi-chat-square-text" style="font-size:3rem;color:#ccc;"></i>
            <p class="mt-3 text-muted fs-5">Henüz hiç başlık açılmamış.</p>
            <?php if (isLoggedIn()): ?>
                <a href="create.php" class="btn btn-ff-primary">
                    <i class="bi bi-plus-lg me-1"></i>İlk Başlığı Sen Aç!
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($posts as $post): ?>
            <div class="col-12">
                <div class="card post-card">
                    <div class="card-body px-4 py-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="flex-grow-1">
                                <div class="mb-1">
                                    <span class="badge-category me-2"><?= e($post['category']) ?></span>
                                </div>
                                <h5 class="card-title mb-1">
                                    <a href="#"><?= e($post['title']) ?></a>
                                </h5>
                                <p class="card-text text-muted mb-2" style="font-size:.92rem;">
                                    <?= e(mb_strimwidth($post['content'], 0, 180, '...')) ?>
                                </p>
                                <small class="text-muted">
                                    <i class="bi bi-person-circle me-1"></i><?= e($post['username']) ?>
                                    &nbsp;&bull;&nbsp;
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                                    <?php if ($post['updated_at'] !== $post['created_at']): ?>
                                        &nbsp;<span class="badge bg-secondary" style="font-size:.7rem;">düzenlendi</span>
                                    <?php endif; ?>
                                </small>
                            </div>

                            <?php if (isLoggedIn() && (int)$_SESSION['user_id'] === (int)$post['user_id']): ?>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="edit.php?id=<?= (int)$post['id'] ?>"
                                   class="btn btn-sm btn-outline-success"
                                   title="Düzenle">
                                    <i class="bi bi-pencil-square me-1"></i>Düzenle
                                </a>
                                <a href="delete.php?id=<?= (int)$post['id'] ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   title="Sil"
                                   onclick="return confirm('Bu başlığı silmek istediğinizden emin misiniz?')">
                                    <i class="bi bi-trash3 me-1"></i>Sil
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>

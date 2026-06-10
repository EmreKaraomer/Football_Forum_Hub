<?php
// login.php - Kullanici Girisi
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$errors   = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']      ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Kullanıcı adı ve şifre boş bırakılamaz.';
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Oturum sabitleme saldirilarini onle
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];

            $_SESSION['flash_success'] = 'Hoş geldiniz, ' . $user['username'] . '!';
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Kullanıcı adı veya şifre hatalı.';
        }
    }
}

$pageTitle = 'Giriş Yap';
require_once 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><i class="bi bi-box-arrow-in-right me-2"></i>Giriş Yap</h1>
        <p class="mb-0">Tartışmalara katılmak için giriş yapın.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card form-card p-4">
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $err): ?>
                                <p class="mb-0"><i class="bi bi-exclamation-circle me-1"></i><?= e($err) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold">Kullanıcı Adı</label>
                            <input type="text" id="username" name="username"
                                   class="form-control"
                                   value="<?= e($username) ?>"
                                   placeholder="Kullanıcı adınız"
                                   required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Şifre</label>
                            <input type="password" id="password" name="password"
                                   class="form-control"
                                   placeholder="••••••••"
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-ff-primary btn-lg">
                                <i class="bi bi-door-open me-1"></i> Giriş Yap
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">
                    <p class="text-center mb-0">
                        Hesabınız yok mu?
                        <a href="register.php" class="text-success fw-semibold">Kayıt Ol</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

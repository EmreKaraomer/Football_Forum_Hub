<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$errors   = [];
$username = '';
$email    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['confirm']       ?? '';

 
    if ($username === '' || strlen($username) < 3) {
        $errors[] = 'Kullanıcı adı en az 3 karakter olmalıdır.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçerli bir e-posta adresi giriniz.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Şifre en az 6 karakter olmalıdır.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Şifreler eşleşmiyor.';
    }

    if (empty($errors)) {
        $pdo = getDB();

        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Bu kullanıcı adı veya e-posta zaten kayıtlı.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hash]);

            $_SESSION['flash_success'] = 'Kayıt başarılı! Şimdi giriş yapabilirsiniz.';
            header('Location: login.php');
            exit;
        }
    }
}

$pageTitle = 'Kayıt Ol';
require_once 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><i class="bi bi-person-plus-fill me-2"></i>Kayıt Ol</h1>
        <p class="mb-0">Foruma katılmak için hesap oluşturun.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
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
                            <label for="username" class="form-label fw-semibold">Kullanıcı Adı</label>
                            <input type="text" id="username" name="username"
                                   class="form-control"
                                   value="<?= e($username) ?>"
                                   placeholder="futbol_sever"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">E-posta</label>
                            <input type="email" id="email" name="email"
                                   class="form-control"
                                   value="<?= e($email) ?>"
                                   placeholder="ornek@mail.com"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Şifre</label>
                            <input type="password" id="password" name="password"
                                   class="form-control"
                                   placeholder="En az 6 karakter"
                                   required>
                        </div>
                        <div class="mb-4">
                            <label for="confirm" class="form-label fw-semibold">Şifre Tekrar</label>
                            <input type="password" id="confirm" name="confirm"
                                   class="form-control"
                                   placeholder="Şifreyi tekrar girin"
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-ff-primary btn-lg">
                                <i class="bi bi-person-check-fill me-1"></i> Kayıt Ol
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">
                    <p class="text-center mb-0">
                        Zaten hesabınız var mı?
                        <a href="login.php" class="text-success fw-semibold">Giriş Yap</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

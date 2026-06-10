<?php
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME) ?></title>

 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ff-green:  #1a6b2f;
            --ff-green-light: #238a3d;
            --ff-gold:   #f5a623;
            --ff-dark:   #111827;
            --ff-card-bg:#ffffff;
        }

        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        /* Navbar */
        .navbar-brand .brand-icon { font-size: 1.4rem; }
        .navbar { background: linear-gradient(135deg, var(--ff-dark) 0%, var(--ff-green) 100%) !important; }
        .navbar .nav-link { color: rgba(255,255,255,.85) !important; font-weight: 500; }
        .navbar .nav-link:hover { color: var(--ff-gold) !important; }
        .navbar-brand { color: #fff !important; font-weight: 700; font-size: 1.3rem; letter-spacing: .5px; }

        /* Sayfa baslik alani */
        .page-hero {
            background: linear-gradient(135deg, var(--ff-dark) 0%, var(--ff-green) 100%);
            color: #fff;
            padding: 2.5rem 0 2rem;
            margin-bottom: 2rem;
        }
        .page-hero h1 { font-weight: 700; }

        /* Kartlar */
        .post-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            transition: box-shadow .2s, transform .2s;
        }
        .post-card:hover {
            box-shadow: 0 6px 24px rgba(0,0,0,.14);
            transform: translateY(-2px);
        }
        .post-card .card-title a {
            color: var(--ff-dark);
            text-decoration: none;
            font-weight: 600;
        }
        .post-card .card-title a:hover { color: var(--ff-green); }

        /* Kategori etiketi */
        .badge-category {
            background-color: var(--ff-green);
            color: #fff;
            font-size: .75rem;
            padding: .3em .7em;
            border-radius: 50px;
        }

        /* Butonlar */
        .btn-ff-primary {
            background: var(--ff-green);
            border-color: var(--ff-green);
            color: #fff;
            font-weight: 600;
        }
        .btn-ff-primary:hover {
            background: var(--ff-green-light);
            border-color: var(--ff-green-light);
            color: #fff;
        }

        /* Form kartlari */
        .form-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,.10);
        }

        /* Footer */
        footer {
            background: var(--ff-dark);
            color: rgba(255,255,255,.65);
        }
        footer a { color: var(--ff-gold); text-decoration: none; }
        footer a:hover { text-decoration: underline; }

        /* Flash mesajlari */
        .alert { border-radius: 10px; }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <span class="brand-icon">⚽</span> <?= e(SITE_NAME) ?>
        </a>
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-label="Menüyü aç/kapat">
            <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="bi bi-house-door me-1"></i>Ana Sayfa</a>
                </li>
                <?php if (isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="create.php"><i class="bi bi-plus-circle me-1"></i>Yeni Başlık</a>
                </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= e($_SESSION['username']) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right me-1"></i>Çıkış
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Giriş</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php"><i class="bi bi-person-plus me-1"></i>Kayıt Ol</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if (!empty($_SESSION['flash_success'])): ?>
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= e($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['flash_success']); endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= e($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['flash_error']); endif; ?>

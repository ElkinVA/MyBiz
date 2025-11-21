<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($meta_title ?? $page['title']) ?> - <?= htmlspecialchars($settings['site']['title'] ?? 'MyBiz') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? '') ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($meta_title ?? $page['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? '') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= BASE_URL . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>
<body>
    <?php include_once APP_ROOT . '/views/templates/header.php'; ?>

    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title"><?= htmlspecialchars($page['title']) ?></h1>
                <nav class="breadcrumbs">
                    <a href="<?= BASE_URL ?>">Главная</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="current"><?= htmlspecialchars($page['title']) ?></span>
                </nav>
            </div>

            <div class="page-body">
                <div class="page-content-editable">
                    <?= $page['content'] ?>
                </div>
            </div>

            <?php if ($page['slug'] === 'contacts'): ?>
                <div class="contact-info">
                    <div class="contact-item">
                        <h3>Телефон</h3>
                        <p><?= htmlspecialchars($settings['contact']['phone'] ?? '') ?></p>
                    </div>
                    <div class="contact-item">
                        <h3>Email</h3>
                        <p><?= htmlspecialchars($settings['contact']['email'] ?? '') ?></p>
                    </div>
                    <div class="contact-item">
                        <h3>Адрес</h3>
                        <p><?= htmlspecialchars($settings['contact']['address'] ?? '') ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include_once APP_ROOT . '/views/templates/footer.php'; ?>

    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="ru" itemscope itemtype="http://schema.org/WebSite">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($metaTitle ?? 'MyBiz - Интернет-магазин') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Лучший интернет-магазин с широким ассортиментом товаров') ?>">
    
    <!-- Schema.org markup -->
    <meta itemprop="name" content="<?= htmlspecialchars($metaTitle ?? 'MyBiz - Интернет-магазин') ?>">
    <meta itemprop="description" content="<?= htmlspecialchars($metaDescription ?? 'Лучший интернет-магазин с широким ассортиментом товаров') ?>">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle ?? 'MyBiz - Интернет-магазин') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? 'Лучший интернет-магазин с широким ассортиментом товаров') ?>">
    <meta property="og:url" content="<?= BASE_URL ?>">
    <meta property="og:site_name" content="MyBiz">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= BASE_URL . $_SERVER['REQUEST_URI'] ?>">
    
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body itemscope itemtype="http://schema.org/WebPage">
    <header class="header" style="
        <?php if (isset($settings['header_background_type']) && $settings['header_background_type'] === 'color'): ?>
            background-color: <?= $settings['header_background_value'] ?? '#ffffff' ?>;
        <?php elseif (isset($settings['header_background_type']) && $settings['header_background_type'] === 'gradient'): ?>
            background: <?= $settings['header_background_value'] ?>;
        <?php elseif (isset($settings['header_background_type']) && $settings['header_background_type'] === 'image'): ?>
            background-image: url('<?= $settings['header_background_value'] ?>');
        <?php endif; ?>
        color: <?= $settings['header_text_color'] ?? '#000000' ?>;
        font-family: <?= $settings['header_font_family'] ?? 'Arial, sans-serif' ?>;
    ">
        <div class="container">
            <div class="header-content">
                <?php if ($settings['show_site_logo'] ?? true): ?>
                    <div class="logo">
                        <?php if (!empty($settings['site_logo'])): ?>
                            <img src="<?= $settings['site_logo'] ?>" alt="<?= $settings['site_name'] ?? 'MyBiz' ?>">
                        <?php else: ?>
                            <span class="logo-text"><?= $settings['site_name'] ?? 'MyBiz' ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <nav class="main-nav">
                    <ul>
                        <li><a href="/">Главная</a></li>
                        <li><a href="/page/about">О нас</a></li>
                        <li><a href="/page/contacts">Контакты</a></li>
                        <li><a href="/admin" class="admin-link">Панель управления</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
<?php include 'templates/admin/header.php'; ?>

<div class="dashboard">
    <h1>Панель управления</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Товары</h3>
            <div class="stat-number"><?= $stats['products_count'] ?></div>
        </div>
        <div class="stat-card">
            <h3>Категории</h3>
            <div class="stat-number"><?= $stats['categories_count'] ?></div>
        </div>
        <div class="stat-card">
            <h3>Слайдеры</h3>
            <div class="stat-number"><?= $stats['sliders_count'] ?></div>
        </div>
        <div class="stat-card">
            <h3>Страницы</h3>
            <div class="stat-number"><?= $stats['pages_count'] ?></div>
        </div>
    </div>

    <div class="quick-actions">
        <h2>Быстрые действия</h2>
        <div class="action-buttons">
            <a href="/admin/products" class="btn btn-primary">Управление товарами</a>
            <a href="/admin/categories" class="btn btn-primary">Управление категориями</a>
            <a href="/admin/sliders" class="btn btn-primary">Управление слайдерами</a>
            <a href="/admin/settings" class="btn btn-secondary">Настройки сайта</a>
        </div>
    </div>
</div>

<?php include 'templates/admin/footer.php'; ?>
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
    <link rel="stylesheet" href="/assets/css/components/header.css">
    <link rel="stylesheet" href="/assets/css/components/slider.css">
    <link rel="stylesheet" href="/assets/css/components/products.css">
    <link rel="stylesheet" href="/assets/css/components/footer.css">
</head>
<body itemscope itemtype="http://schema.org/WebPage">

<!-- Header -->
<header class="header" style="<?= $headerStyles ?? '' ?>" itemscope itemtype="http://schema.org/WPHeader">
    <div class="container">
        <div class="header-content">
            <?php if ($settings['logo_display'] ?? true): ?>
            <div class="logo" itemprop="logo">
                <a href="/" itemprop="url">
                    <?php if (!empty($settings['logo_image'])): ?>
                        <img src="/assets/images/uploads/<?= htmlspecialchars($settings['logo_image']) ?>" 
                             alt="<?= htmlspecialchars($settings['site_name'] ?? 'MyBiz') ?>" 
                             itemprop="image">
                    <?php else: ?>
                        <span class="logo-text"><?= htmlspecialchars($settings['site_name'] ?? 'MyBiz') ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <?php endif; ?>

            <?php if ($settings['site_name_display'] ?? true): ?>
            <div class="site-name">
                <h1 itemprop="name"><?= htmlspecialchars($settings['site_name'] ?? 'MyBiz') ?></h1>
            </div>
            <?php endif; ?>

            <div class="header-actions">
                <a href="/admin" class="admin-link" title="Панель управления" aria-label="Панель управления">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12 3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04.32.07.64.07.97 0 2.73-2.08 5-4.75 5s-4.75-2.27-4.75-5 2.08-5 4.75-5c1.03 0 1.97.3 2.77.8A7.05 7.05 0 0 0 12 5c-3.87 0-7 3.13-7 7s3.13 7 7 7c3.47 0 6.36-2.54 6.86-5.88l-1.43-.35z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Top Slider -->
<?php if (!empty($topSliders) && ($settings['top_slider_enabled'] ?? true)): ?>
<section class="slider-section top-slider" aria-label="Основной слайдер">
    <div class="slider-container" id="topSlider">
        <?php foreach ($topSliders as $index => $slider): ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?>" 
             style="<?= $this->getSliderStyle($slider) ?>" 
             role="tabpanel" 
             aria-label="Слайд <?= $index + 1 ?>">
            <div class="slide-content" style="<?= $this->getTextStyle($slider) ?>">
                <?php if (!empty($slider['title'])): ?>
                    <h2 class="slide-title"><?= htmlspecialchars($slider['title']) ?></h2>
                <?php endif; ?>
                <?php if (!empty($slider['description'])): ?>
                    <div class="slide-description"><?= htmlspecialchars($slider['description']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (count($topSliders) > 1): ?>
    <div class="slider-controls">
        <button class="slider-prev" aria-label="Предыдущий слайд">‹</button>
        <div class="slider-dots" role="tablist">
            <?php foreach ($topSliders as $index => $slider): ?>
            <button class="slider-dot <?= $index === 0 ? 'active' : '' ?>" 
                    data-slide="<?= $index ?>" 
                    role="tab" 
                    aria-label="Перейти к слайду <?= $index + 1 ?>"
                    aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"></button>
            <?php endforeach; ?>
        </div>
        <button class="slider-next" aria-label="Следующий слайд">›</button>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- Main Content -->
<main class="main-content" itemprop="mainContentOfPage">
    <div class="container">
        <!-- Categories Section -->
        <?php if (!empty($categories) && ($settings['categories_enabled'] ?? true)): ?>
        <section class="categories-section" aria-labelledby="categories-heading">
            <h2 id="categories-heading" class="section-title">Категории товаров</h2>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                <div class="category-card" 
                     style="<?= $this->getCategoryStyle($category) ?>" 
                     itemscope 
                     itemtype="http://schema.org/Category">
                    <div class="category-content" style="<?= $this->getTextStyle($category) ?>">
                        <h3 class="category-name" itemprop="name"><?= htmlspecialchars($category['name']) ?></h3>
                        <?php if (!empty($category['description'])): ?>
                            <p class="category-description" itemprop="description"><?= htmlspecialchars($category['description']) ?></p>
                        <?php endif; ?>
                        <meta itemprop="numberOfItems" content="<?= $category['product_count'] ?? 0 ?>">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Products Section -->
        <section class="products-section" aria-labelledby="products-heading">
            <div class="section-header">
                <h2 id="products-heading" class="section-title">Наши товары</h2>
                
                <!-- Search and Filter -->
                <div class="products-controls">
                    <div class="search-box">
                        <input type="text" 
                               id="productSearch" 
                               placeholder="Поиск товаров..." 
                               aria-label="Поиск товаров">
                        <button type="button" id="searchButton" aria-label="Искать">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <?php if (!empty($categories)): ?>
                    <select id="categoryFilter" aria-label="Фильтр по категориям">
                        <option value="">Все категории</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Products Grid with Schema.org markup -->
            <div class="products-grid" 
                 itemprop="mainEntity" 
                 itemscope 
                 itemtype="http://schema.org/ItemList">
                <?php foreach ($products as $product): ?>
                <div class="product-card" 
                     itemprop="itemListElement" 
                     itemscope 
                     itemtype="http://schema.org/Product"
                     style="<?= $this->getProductStyle($product) ?>">
                    
                    <div class="product-image">
                        <?php if (!empty($product['image'])): ?>
                        <img src="/assets/images/uploads/products/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             itemprop="image"
                             loading="lazy">
                        <?php else: ?>
                        <div class="product-image-placeholder">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info" style="<?= $this->getTextStyle($product) ?>">
                        <h3 class="product-name" itemprop="name"><?= htmlspecialchars($product['name']) ?></h3>
                        
                        <?php if (!empty($product['description'])): ?>
                        <p class="product-description" itemprop="description">
                            <?= htmlspecialchars(mb_strimwidth($product['description'], 0, 150, '...')) ?>
                        </p>
                        <?php endif; ?>
                        
                        <div class="product-meta">
                            <?php if (!empty($product['category_name'])): ?>
                            <span class="product-category" itemprop="category">
                                <?= htmlspecialchars($product['category_name']) ?>
                            </span>
                            <?php endif; ?>
                            
                            <div class="product-price" 
                                 itemprop="offers" 
                                 itemscope 
                                 itemtype="http://schema.org/Offer">
                                <span class="price-currency" itemprop="priceCurrency" content="RUB">₽</span>
                                <span class="price-value" itemprop="price" content="<?= $product['price'] ?>">
                                    <?= number_format($product['price'], 0, ',', ' ') ?>
                                </span>
                                <link itemprop="availability" href="http://schema.org/InStock" />
                                <meta itemprop="url" content="<?= BASE_URL ?>/product/<?= $product['id'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination and Load More -->
            <div class="products-footer">
                <?php if ($hasMoreProducts): ?>
                <button id="loadMoreBtn" class="load-more-btn" data-page="2" data-category="" data-search="">
                    Показать ещё
                    <span class="loading-spinner" style="display: none;">Загрузка...</span>
                </button>
                <?php endif; ?>
                
                <div class="products-count" aria-live="polite">
                    Показано <span id="shownCount"><?= count($products) ?></span> из 
                    <span id="totalCount"><?= $totalProducts ?></span> товаров
                </div>
            </div>
        </section>

        <!-- SEO Text Block -->
        <?php if (!empty($settings['seo_text']) && ($settings['seo_text_enabled'] ?? true)): ?>
        <section class="seo-section" aria-labelledby="seo-heading">
            <div class="seo-content" itemprop="text">
                <h2 id="seo-heading" class="visually-hidden">Информация о магазине</h2>
                <?= nl2br(htmlspecialchars($settings['seo_text'])) ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>

<!-- Bottom Slider -->
<?php if (!empty($bottomSliders) && ($settings['bottom_slider_enabled'] ?? true)): ?>
<section class="slider-section bottom-slider" aria-label="Дополнительный слайдер">
    <div class="slider-container" id="bottomSlider">
        <?php foreach ($bottomSliders as $index => $slider): ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?>" 
             style="<?= $this->getSliderStyle($slider) ?>" 
             role="tabpanel" 
             aria-label="Слайд <?= $index + 1 ?>">
            <div class="slide-content" style="<?= $this->getTextStyle($slider) ?>">
                <?php if (!empty($slider['title'])): ?>
                    <h2 class="slide-title"><?= htmlspecialchars($slider['title']) ?></h2>
                <?php endif; ?>
                <?php if (!empty($slider['description'])): ?>
                    <div class="slide-description"><?= htmlspecialchars($slider['description']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (count($bottomSliders) > 1): ?>
    <div class="slider-controls">
        <button class="slider-prev" aria-label="Предыдущий слайд">‹</button>
        <div class="slider-dots" role="tablist">
            <?php foreach ($bottomSliders as $index => $slider): ?>
            <button class="slider-dot <?= $index === 0 ? 'active' : '' ?>" 
                    data-slide="<?= $index ?>" 
                    role="tab" 
                    aria-label="Перейти к слайду <?= $index + 1 ?>"
                    aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"></button>
            <?php endforeach; ?>
        </div>
        <button class="slider-next" aria-label="Следующий слайд">›</button>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- Footer -->
<footer class="footer" style="<?= $footerStyles ?? '' ?>" itemscope itemtype="http://schema.org/WPFooter">
    <div class="container">
        <div class="footer-content">
            <!-- Navigation -->
            <div class="footer-section">
                <h3 class="footer-title">Навигация</h3>
                <nav class="footer-nav" aria-label="Дополнительная навигация">
                    <ul>
                        <li><a href="/" itemprop="url">Главная</a></li>
                        <li><a href="/about" itemprop="url">О нас</a></li>
                        <li><a href="/catalog" itemprop="url">Каталог</a></li>
                        <li><a href="/contacts" itemprop="url">Контакты</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Information Pages -->
            <div class="footer-section">
                <h3 class="footer-title">Информация</h3>
                <nav class="footer-nav" aria-label="Информационные страницы">
                    <ul>
                        <li><a href="/delivery" itemprop="url">Доставка и оплата</a></li>
                        <li><a href="/guarantee" itemprop="url">Гарантия качества</a></li>
                        <li><a href="/faq" itemprop="url">Вопросы и ответы</a></li>
                        <li><a href="/privacy" itemprop="url">Политика конфиденциальности</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Contact Information -->
            <div class="footer-section">
                <h3 class="footer-title">Контакты</h3>
                <div class="contact-info" itemprop="contactPoint" itemscope itemtype="http://schema.org/ContactPoint">
                    <?php if (!empty($settings['contact_phone'])): ?>
                    <div class="contact-item">
                        <strong>Телефон:</strong>
                        <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $settings['contact_phone'])) ?>" 
                           itemprop="telephone">
                            <?= htmlspecialchars($settings['contact_phone']) ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['contact_email'])): ?>
                    <div class="contact-item">
                        <strong>Email:</strong>
                        <a href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>" 
                           itemprop="email">
                            <?= htmlspecialchars($settings['contact_email']) ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['contact_address'])): ?>
                    <div class="contact-item">
                        <strong>Адрес:</strong>
                        <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <span itemprop="streetAddress"><?= htmlspecialchars($settings['contact_address']) ?></span>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Legal Information -->
            <div class="footer-section">
                <h3 class="footer-title">Юридическая информация</h3>
                <div class="legal-info">
                    <?php if (!empty($settings['legal_name'])): ?>
                    <div class="legal-item" itemprop="legalName"><?= htmlspecialchars($settings['legal_name']) ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['legal_inn'])): ?>
                    <div class="legal-item">ИНН: <?= htmlspecialchars($settings['legal_inn']) ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['legal_ogrn'])): ?>
                    <div class="legal-item">ОГРН: <?= htmlspecialchars($settings['legal_ogrn']) ?></div>
                    <?php endif; ?>
                    
                    <div class="legal-item">&copy; <?= date('Y') ?> MyBiz. Все права защищены.</div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Schema.org Organization Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?= htmlspecialchars($settings['site_name'] ?? 'MyBiz') ?>",
    "url": "<?= BASE_URL ?>",
    "logo": "<?= BASE_URL ?>/assets/images/uploads/<?= htmlspecialchars($settings['logo_image'] ?? '') ?>",
    "description": "<?= htmlspecialchars($settings['seo_description'] ?? 'Лучший интернет-магазин') ?>",
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>",
        "email": "<?= htmlspecialchars($settings['contact_email'] ?? '') ?>",
        "contactType": "customer service"
    },
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= htmlspecialchars($settings['contact_address'] ?? '') ?>"
    }
}
</script>

<!-- Scripts -->
<script src="/assets/js/main.js"></script>
<script src="/assets/js/slider.js"></script>
<script src="/assets/js/products.js"></script>
<script src="/assets/js/utils/helpers.js"></script>
<script src="/assets/js/utils/ajax.js"></script>

<script>
// Initialize products manager
document.addEventListener('DOMContentLoaded', function() {
    const productsManager = new ProductsManager({
        loadMoreBtn: '#loadMoreBtn',
        searchInput: '#productSearch',
        categoryFilter: '#categoryFilter',
        searchButton: '#searchButton',
        shownCount: '#shownCount',
        totalCount: '#totalCount'
    });
    
    // Initialize sliders
    if (document.getElementById('topSlider')) {
        const topSlider = new SliderManager('#topSlider');
    }
    
    if (document.getElementById('bottomSlider')) {
        const bottomSlider = new SliderManager('#bottomSlider');
    }
});
</script>

</body>
</html>
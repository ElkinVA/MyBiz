<?php
define('SITE_NAME', 'MyBiz');
define('BASE_URL', 'http://localhost/mybi z');
define('ADMIN_URL', BASE_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../../public/assets/images/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('APP_ROOT', dirname(__DIR__, 2));
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');
define('STATUS_ACTIVE', 1);
define('STATUS_INACTIVE', 0);
define('STATUS_DRAFT', 2);

// Пути для разных типов загрузок
define('UPLOAD_SLIDERS', 'sliders/');
define('UPLOAD_PRODUCTS', 'products/');
define('UPLOAD_CATEGORIES', 'categories/');
?>
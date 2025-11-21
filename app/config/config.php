<?php

require_once 'environment.php';

return [
    // Application Settings
    'database' => [
        'driver' => 'sqlsrv', // Изменить с 'mysql' на 'sqlsrv'
        'host' => 'localhost',
        'database' => 'mybiz',
        'username' => 'your_username',
        'password' => 'your_password',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
    
    'application' => [
        'name' => 'MyBiz',
        'version' => '1.0'
    ],
    
    // ... остальная конфигурация

    'app' => [
        'debug' => Environment::get('APP_DEBUG', 'false') === 'true',
        'timezone' => Environment::get('APP_TIMEZONE', 'Europe/Moscow'),
        'name' => 'MyBiz',
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
        'env' => $_ENV['APP_ENV'] ?? 'production'
    ],

    // Database Configuration
    'database' => [
        'host' => Environment::get('DB_HOST', 'localhost'),
        'port' => Environment::get('DB_PORT', '3306'),
        'name' => Environment::get('DB_NAME', 'mybiz_production'),
        'user' => Environment::get('DB_USER', 'mybiz_user'),
        'pass' => Environment::get('DB_PASS', ''),
        'charset' => Environment::get('DB_CHARSET', 'utf8mb4'),
        'collation' => Environment::get('DB_COLLATION', 'utf8mb4_unicode_ci')
    ],

    // Security Configuration
    'security' => [
        'secret_key' => Environment::get('SECRET_KEY', 'default_secret_key_change_in_production'),
        'csrf_secret' => Environment::get('CSRF_SECRET', 'default_csrf_secret'),
        'session_secret' => Environment::get('SESSION_SECRET', 'default_session_secret'),
        'hsts_enabled' => Environment::get('HSTS_ENABLED', 'true') === 'true',
        'csp_enabled' => Environment::get('CSP_ENABLED', 'true') === 'true'
    ],

    // File Upload Configuration
    'upload' => [
        'max_size' => (int)Environment::get('UPLOAD_MAX_SIZE', 10485760),
        'allowed_types' => explode(',', Environment::get('UPLOAD_ALLOWED_TYPES', 'image/jpeg,image/png,image/gif')),
        'upload_path' => Environment::get('UPLOAD_PATH', dirname(__DIR__, 2) . '/public/assets/images/uploads')
    ],

    // Email Configuration
    'email' => [
        'smtp_host' => Environment::get('SMTP_HOST', 'localhost'),
        'smtp_port' => (int)Environment::get('SMTP_PORT', 587),
        'smtp_user' => Environment::get('SMTP_USER', ''),
        'smtp_pass' => Environment::get('SMTP_PASS', ''),
        'from_name' => Environment::get('SMTP_FROM_NAME', 'MyBiz Shop'),
        'from_email' => Environment::get('SMTP_FROM_EMAIL', 'noreply@mybiz-shop.ru')
    ],

    // Cache Configuration
    'cache' => [
        'driver' => Environment::get('CACHE_DRIVER', 'file'),
        'ttl' => (int)Environment::get('CACHE_TTL', 3600),
        'path' => Environment::get('CACHE_PATH', dirname(__DIR__, 2) . '/storage/cache'),
        'enabled' => Environment::get('CACHE_ENABLED', 'true') === 'true'
    ],

    // Performance Settings
    'performance' => [
        'gzip_compression' => Environment::get('GZIP_COMPRESSION', 'true') === 'true',
        'brotli_compression' => Environment::get('BROTLI_COMPRESSION', 'true') === 'true',
        'minify_html' => Environment::get('MINIFY_HTML', 'true') === 'true'
    ],

    // Error Handling
    'error' => [
        'log_level' => Environment::get('LOG_LEVEL', 'error'),
        'error_reporting' => Environment::get('ERROR_REPORTING', E_ALL & ~E_DEPRECATED & ~E_STRICT),
        'display_errors' => Environment::get('DISPLAY_ERRORS', 'Off') === 'On',
        'log_file' => Environment::get('LOG_FILE', dirname(__DIR__, 2) . '/storage/logs/production.log')
    ],
    
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'name' => $_ENV['DB_NAME'] ?? 'mybiz',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4'
    ],
    'auth' => [
        'session_key' => 'admin_user',
        'login_route' => '/admin/login'
    ]
];
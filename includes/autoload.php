<?php

spl_autoload_register(function ($className) {
    // Базовые пути для поиска классов
    $basePaths = [
        __DIR__ . '/../app/core/',
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/'
    ];

    // Преобразование namespace в путь к файлу
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    // Поиск файла в базовых путях
    foreach ($basePaths as $basePath) {
        $fullPath = $basePath . $fileName;
        if (file_exists($fullPath)) {
            require_once $fullPath;
            return true;
        }
    }

    // Автозагрузка для классов без namespace
    $simpleClassPaths = [
        'Controller' => 'core/Controller.php',
        'Auth' => 'core/Auth.php',
        'Session' => 'core/Session.php',
        'Validator' => 'core/Validator.php',
        'Upload' => 'core/Upload.php',
        'Router' => 'core/Router.php',
        'Security' => 'core/Security.php',
        'Database' => 'core/Database.php',
        'AdminModel' => 'models/AdminModel.php',
        'BaseModel' => 'models/BaseModel.php',
        'CategoryModel' => 'models/CategoryModel.php',
        'PageModel' => 'models/PageModel.php',
        'ProductModel' => 'models/ProductModel.php',
        'SettingsModel' => 'models/SettingsModel.php',
        'SliderModel' => 'models/SliderModel.php',
        'AdminController' => 'controllers/AdminController.php',
        'ApiController' => 'controllers/ApiController.php',
        'HomeController' => 'controllers/HomeController.php',
        'PageController' => 'controllers/PageController.php'
    ];

    if (isset($simpleClassPaths[$className])) {
        $filePath = __DIR__ . '/../app/' . $simpleClassPaths[$className];
        if (file_exists($filePath)) {
            require_once $filePath;
            return true;
        }
    }

    return false;
});

// Загрузка конфигурации
if (file_exists(__DIR__ . '/../app/config/config.php')) {
    require_once __DIR__ . '/../app/config/config.php';
}

// Инициализация констант
if (file_exists(__DIR__ . '/../app/config/constants.php')) {
    require_once __DIR__ . '/../app/config/constants.php';
}

// Определение APP_ROOT если не определено
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}
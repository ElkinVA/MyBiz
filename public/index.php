<?php
// Настройка отображения ошибок (для разработки)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Определяем корневую директорию
define('APP_ROOT', dirname(__DIR__));

// Подключаем автозагрузчик и конфигурацию
require_once APP_ROOT . '/includes/autoload.php';
require_once APP_ROOT . '/app/config/config.php';
require_once __DIR__ . '/../includes/autoload.php';
require_once __DIR__ . '/../app/config/routes.php';

// Инициализируем и запускаем маршрутизатор
use App\Core\Router;

$router = new Router();
$routeInfo = $router->route($_SERVER['REQUEST_URI']);

// Загружаем контроллер
$controllerName = "App\\Controllers\\" . $routeInfo['controller'];
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    $action = $routeInfo['action'];
    
    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $routeInfo['params']);
    } else {
        throw new Exception("Method $action not found in controller $controllerName");
    }
} else {
    throw new Exception("Controller $controllerName not found");
}

session_start();

try {
    $router = new Router();
$router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    require_once __DIR__ . '/../app/views/errors/500.php';
}
?>
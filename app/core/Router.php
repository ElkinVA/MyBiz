<?php
class Router {
    private $routes = [];
    
    public function __construct($routes) {
        $this->routes = $routes;
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            list($controller, $action) = explode('@', $handler);
            
            $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                $controllerInstance->$action();
            }
        } else {
            // 404 handling
            http_response_code(404);
            $this->show404();
        }
    }
    
    private function show404() {
        require_once __DIR__ . '/../views/errors/404.php';
    }
}
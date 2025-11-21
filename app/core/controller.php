<?php

class Controller
{
    protected $db;
    protected $auth;
    protected $session;
    protected $validator;
    
    public function __construct()
    {
        // Инициализация основных компонентов
        $this->session = new Session();
        $this->auth = new Auth();
        $this->validator = new Validator();
        
        // Инициализация базы данных, если она доступна
        if (class_exists('Database')) {
            $this->db = Database::getInstance();
        }
    }
    
    protected function render($view, $data = [])
    {
        extract($data);
        
        // Определяем базовый путь к представлениям
        $viewPath = dirname(__DIR__) . "/views/{$view}.php";
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            // Если представление не найдено, показываем ошибку 500
            $this->showError(500, "View '{$view}' not found");
        }
    }
    
    protected function redirect($url, $permanent = false)
    {
        if ($permanent) {
            header('HTTP/1.1 301 Moved Permanently');
        }
        header("Location: {$url}");
        exit;
    }
    
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    protected function getInput($key = null, $default = null)
    {
        if ($key === null) {
            return array_merge($_GET, $_POST);
        }
        
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    protected function getFile($key)
    {
        return $_FILES[$key] ?? null;
    }
    
    protected function showError($code = 500, $message = 'Internal Server Error')
    {
        http_response_code($code);
        
        $errorView = dirname(__DIR__) . "/views/errors/{$code}.php";
        if (file_exists($errorView)) {
            require $errorView;
        } else {
            // Fallback error display
            echo "<h1>Error {$code}</h1>";
            echo "<p>{$message}</p>";
        }
        exit;
    }
    
    protected function requireAuth()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect('/admin/login');
        }
    }
    
    protected function requireAdmin()
    {
        $this->requireAuth();
        
        if (!$this->auth->isAdmin()) {
            $this->showError(403, 'Access Denied');
        }
    }
    
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    protected function validate($data, $rules)
    {
        return $this->validator->validate($data, $rules);
    }
    
    protected function uploadFile($file, $destination, $allowedTypes = [])
    {
        $upload = new Upload();
        return $upload->process($file, $destination, $allowedTypes);
    }
}
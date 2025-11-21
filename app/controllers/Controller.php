<?php
namespace App\Controllers;

use App\Models\Database;

class Controller {
    protected $db;
    protected $data = [];
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function render($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        
        // Extract data to variables
        extract($this->data);
        
        // Load header
        require_once __DIR__ . '/../views/templates/header.php';
        
        // Load main view
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("View file not found: $viewFile");
        }
        
        // Load footer
        require_once __DIR__ . '/../views/templates/footer.php';
    }
    
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("View {$view} not found");
        }
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    
}
?>
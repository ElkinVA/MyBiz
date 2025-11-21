<?php
namespace App\Controllers;

class HomeController extends Controller {
    
    private $settingsModel;
    private $sliderModel;
    private $categoryModel;
    private $productModel;

    public function __construct() {
        $this->settingsModel = new SettingsModel();
        $this->sliderModel = new SliderModel();
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
    }

    public function index() {
        try {
            // Получаем настройки сайта
            $settings = $this->settingsModel->getAllGrouped();
            
            // Получаем активные слайдеры
            $topSliders = $this->sliderModel->getActiveByPosition('top');
            $bottomSliders = $this->sliderModel->getActiveByPosition('bottom');
            
            // Получаем активные категории
            $categories = $this->categoryModel->getAllActive();
            
            // Получаем товары с пагинацией
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            
            $productsData = $this->productModel->getPaginatedProducts($page, 12, $category_id, $search);
            
            $data = [
                'settings' => $settings,
                'topSliders' => $topSliders,
                'bottomSliders' => $bottomSliders,
                'categories' => $categories,
                'products' => $productsData['products'],
                'pagination' => $productsData['pagination'],
                'currentCategory' => $category_id,
                'searchQuery' => $search
            ];
            
            $this->render('home/index', $data);
            
        } catch (Exception $e) {
            error_log("HomeController error: " . $e->getMessage());
            $this->render('errors/500', [], 500);
        }
    }

    private function getActiveSliders() {
        // Временные данные - позже заменим на запросы к БД
        return [
            [
                'title' => 'Добро пожаловать в MyBiz',
                'description' => 'Лучшие товары по выгодным ценам',
                'background_type' => 'gradient',
                'background_value' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
            ]
        ];
    }
    
    private function getActiveCategories() {
        return [
            ['id' => 1, 'name' => 'Электроника', 'products_count' => 45],
            ['id' => 2, 'name' => 'Одежда', 'products_count' => 67],
            // ... другие категории
        ];
    }
    
    private function getFeaturedProducts() {
        return [
            ['id' => 1, 'name' => 'Товар 1', 'price' => 2999, 'image' => 'product1.jpg'],
            ['id' => 2, 'name' => 'Товар 2', 'price' => 3999, 'image' => 'product2.jpg'],
            // ... другие товары
        ];
    }
    public function loadMoreProducts() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAjax()) {
            $this->json(['error' => 'Invalid request'], 400);
            return;
        }

        try {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $category_id = isset($_POST['category']) ? (int)$_POST['category'] : null;
            $search = isset($_POST['search']) ? $_POST['search'] : '';
            
            $productsData = $this->productModel->getPaginatedProducts($page, 12, $category_id, $search);
            
            $this->json([
                'success' => true,
                'products' => $productsData['products'],
                'hasMore' => $productsData['pagination']['hasNextPage']
            ]);
            
        } catch (Exception $e) {
            error_log("LoadMoreProducts error: " . $e->getMessage());
            $this->json(['error' => 'Server error'], 500);
        }
    }

    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
?>
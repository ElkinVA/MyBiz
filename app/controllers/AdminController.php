<?php
class AdminController extends Controller
{
    private $auth;
    private $session;
    private $validator;
    
    public function __construct()
    {
        $this->auth = new Auth();
        $this->session = new Session();
        $this->validator = new Validator();
        
        // Проверка аутентификации для всех методов кроме login
        if (!$this->auth->isLoggedIn() && $this->getCurrentMethod() != 'login') {
            $this->redirect('/admin/login');
        }
    }
    
    private function getCurrentMethod()
    {
        $route = $_SERVER['REQUEST_URI'];
        $parts = explode('/', $route);
        return end($parts);
    }
    
    // ДАШБОРД
    public function dashboard()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $sliderModel = new SliderModel();
        $pageModel = new PageModel();
        
        $stats = [
            'total_products' => $productModel->getCount(),
            'total_categories' => $categoryModel->getCount(),
            'active_sliders' => $sliderModel->getActiveCount(),
            'published_pages' => $pageModel->getPublishedCount()
        ];
        
        $recentProducts = $productModel->getRecent(5);
        
        $this->render('admin/dashboard', [
            'stats' => $stats,
            'recentProducts' => $recentProducts
        ]);
    }
    
    // ==================== НАСТРОЙКИ ====================
    
    public function settings()
    {
        $settingsModel = new SettingsModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSettingsUpdate($settingsModel);
        }
        
        $settings = $settingsModel->getAllGrouped();
        $this->render('admin/settings/general', ['settings' => $settings]);
    }
    
    public function headerSettings()
    {
        $settingsModel = new SettingsModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSettingsUpdate($settingsModel);
        }
        
        $settings = $settingsModel->getByGroup('header');
        $this->render('admin/settings/header', ['settings' => $settings]);
    }
    
    public function designSettings()
    {
        $settingsModel = new SettingsModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSettingsUpdate($settingsModel);
        }
        
        $settings = $settingsModel->getByGroup('design');
        $this->render('admin/settings/design', ['settings' => $settings]);
    }
    
    private function handleSettingsUpdate($settingsModel)
    {
        $errors = [];
        
        foreach ($_POST as $key => $value) {
            if (!$settingsModel->updateSetting($key, $value)) {
                $errors[] = "Ошибка обновления настройки: {$key}";
            }
        }
        
        // Обработка загрузки файлов (логотип)
        if (!empty($_FILES['logo']['name'])) {
            $upload = new Upload();
            $logoPath = $upload->handleImageUpload($_FILES['logo'], 'logos');
            if ($logoPath) {
                $settingsModel->updateSetting('site_logo', $logoPath);
            } else {
                $errors[] = "Ошибка загрузки логотипа";
            }
        }
        
        if (empty($errors)) {
            $this->session->set('success', 'Настройки успешно обновлены');
        } else {
            $this->session->set('errors', $errors);
        }
        
        $this->redirect($_SERVER['REQUEST_URI']);
    }
    
    // ==================== СЛАЙДЕРЫ ====================
    
    public function sliders()
    {
        $sliderModel = new SliderModel();
        $sliders = $sliderModel->getAll();
        
        $this->render('admin/sliders/list', ['sliders' => $sliders]);
    }
    
    public function createSlider()
    {
        $sliderModel = new SliderModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateSliderData($_POST);
            
            if (empty($errors)) {
                $sliderId = $sliderModel->create($this->prepareSliderData($_POST));
                
                if ($sliderId) {
                    // Обработка загрузки изображения
                    if (!empty($_FILES['background_image']['name'])) {
                        $upload = new Upload();
                        $imagePath = $upload->handleImageUpload($_FILES['background_image'], 'sliders');
                        if ($imagePath) {
                            $sliderModel->update($sliderId, ['background_value' => $imagePath]);
                        }
                    }
                    
                    $this->session->set('success', 'Слайд успешно создан');
                    $this->redirect('/admin/sliders');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/sliders/edit', ['slider' => null]);
    }
    
    public function editSlider($id)
    {
        $sliderModel = new SliderModel();
        $slider = $sliderModel->find($id);
        
        if (!$slider) {
            $this->session->set('errors', ['Слайд не найден']);
            $this->redirect('/admin/sliders');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateSliderData($_POST);
            
            if (empty($errors)) {
                $updateData = $this->prepareSliderData($_POST);
                
                // Обработка загрузки изображения
                if (!empty($_FILES['background_image']['name'])) {
                    $upload = new Upload();
                    $imagePath = $upload->handleImageUpload($_FILES['background_image'], 'sliders');
                    if ($imagePath) {
                        $updateData['background_value'] = $imagePath;
                        // Удаляем старое изображение
                        if ($slider['background_type'] === 'image' && $slider['background_value']) {
                            $this->deleteFile($slider['background_value']);
                        }
                    }
                }
                
                if ($sliderModel->update($id, $updateData)) {
                    $this->session->set('success', 'Слайд успешно обновлен');
                    $this->redirect('/admin/sliders');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/sliders/edit', ['slider' => $slider]);
    }
    
    public function deleteSlider($id)
    {
        $sliderModel = new SliderModel();
        $slider = $sliderModel->find($id);
        
        if ($slider) {
            // Удаляем изображение если есть
            if ($slider['background_type'] === 'image' && $slider['background_value']) {
                $this->deleteFile($slider['background_value']);
            }
            
            if ($sliderModel->delete($id)) {
                $this->session->set('success', 'Слайд успешно удален');
            } else {
                $this->session->set('errors', ['Ошибка при удалении слайда']);
            }
        } else {
            $this->session->set('errors', ['Слайд не найден']);
        }
        
        $this->redirect('/admin/sliders');
    }
    
    public function toggleSlider($id)
    {
        $sliderModel = new SliderModel();
        $slider = $sliderModel->find($id);
        
        if ($slider) {
            $newStatus = $slider['is_active'] ? 0 : 1;
            $sliderModel->update($id, ['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'активирован' : 'деактивирован';
            $this->session->set('success', "Слайд успешно {$statusText}");
        } else {
            $this->session->set('errors', ['Слайд не найден']);
        }
        
        $this->redirect('/admin/sliders');
    }
    
    private function validateSliderData($data)
    {
        $rules = [
            'title' => 'required|max:255',
            'slider_position' => 'required|in:top,bottom',
            'background_type' => 'required|in:color,gradient,image'
        ];
        
        return $this->validator->validate($data, $rules);
    }
    
    private function prepareSliderData($data)
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'background_type' => $data['background_type'],
            'background_value' => $data['background_value'] ?? '',
            'text_content' => $data['text_content'] ?? '',
            'font_family' => $data['font_family'] ?? '',
            'text_color' => $data['text_color'] ?? '',
            'slider_position' => $data['slider_position'],
            'sort_order' => intval($data['sort_order'] ?? 0),
            'is_active' => isset($data['is_active']) ? 1 : 0
        ];
    }
    
    // ==================== КАТЕГОРИИ ====================
    
    public function categories()
    {
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllWithProductCount();
        
        $this->render('admin/categories/list', ['categories' => $categories]);
    }
    
    public function createCategory()
    {
        $categoryModel = new CategoryModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCategoryData($_POST);
            
            if (empty($errors)) {
                $categoryId = $categoryModel->create($this->prepareCategoryData($_POST));
                
                if ($categoryId) {
                    // Обработка загрузки изображения
                    if (!empty($_FILES['background_image']['name'])) {
                        $upload = new Upload();
                        $imagePath = $upload->handleImageUpload($_FILES['background_image'], 'categories');
                        if ($imagePath) {
                            $categoryModel->update($categoryId, [
                                'background_type' => 'image',
                                'background_value' => $imagePath
                            ]);
                        }
                    }
                    
                    $this->session->set('success', 'Категория успешно создана');
                    $this->redirect('/admin/categories');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/categories/edit', ['category' => null]);
    }
    
    public function editCategory($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->session->set('errors', ['Категория не найдена']);
            $this->redirect('/admin/categories');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCategoryData($_POST);
            
            if (empty($errors)) {
                $updateData = $this->prepareCategoryData($_POST);
                
                // Обработка загрузки изображения
                if (!empty($_FILES['background_image']['name'])) {
                    $upload = new Upload();
                    $imagePath = $upload->handleImageUpload($_FILES['background_image'], 'categories');
                    if ($imagePath) {
                        $updateData['background_type'] = 'image';
                        $updateData['background_value'] = $imagePath;
                        // Удаляем старое изображение
                        if ($category['background_type'] === 'image' && $category['background_value']) {
                            $this->deleteFile($category['background_value']);
                        }
                    }
                }
                
                if ($categoryModel->update($id, $updateData)) {
                    $this->session->set('success', 'Категория успешно обновлена');
                    $this->redirect('/admin/categories');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/categories/edit', ['category' => $category]);
    }
    
    public function deleteCategory($id)
    {
        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();
        
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->session->set('errors', ['Категория не найдена']);
            $this->redirect('/admin/categories');
        }
        
        // Проверяем, есть ли товары в категории
        $productsInCategory = $productModel->getCountByCategory($id);
        if ($productsInCategory > 0) {
            $this->session->set('errors', [
                "Невозможно удалить категорию. В ней содержится {$productsInCategory} товаров."
            ]);
            $this->redirect('/admin/categories');
        }
        
        // Удаляем изображение если есть
        if ($category['background_type'] === 'image' && $category['background_value']) {
            $this->deleteFile($category['background_value']);
        }
        
        if ($categoryModel->delete($id)) {
            $this->session->set('success', 'Категория успешно удалена');
        } else {
            $this->session->set('errors', ['Ошибка при удалении категории']);
        }
        
        $this->redirect('/admin/categories');
    }
    
    public function toggleCategory($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);
        
        if ($category) {
            $newStatus = $category['is_active'] ? 0 : 1;
            $categoryModel->update($id, ['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'активирована' : 'деактивирована';
            $this->session->set('success', "Категория успешно {$statusText}");
        } else {
            $this->session->set('errors', ['Категория не найдена']);
        }
        
        $this->redirect('/admin/categories');
    }
    
    private function validateCategoryData($data)
    {
        $rules = [
            'name' => 'required|max:255',
            'background_type' => 'required|in:color,gradient,image'
        ];
        
        return $this->validator->validate($data, $rules);
    }
    
    private function prepareCategoryData($data)
    {
        return [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'background_type' => $data['background_type'],
            'background_value' => $data['background_value'] ?? '',
            'text_color' => $data['text_color'] ?? '',
            'font_family' => $data['font_family'] ?? '',
            'sort_order' => intval($data['sort_order'] ?? 0),
            'is_active' => isset($data['is_active']) ? 1 : 0
        ];
    }
    
    // ==================== ТОВАРЫ ====================
    
    public function products()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        
        $page = $_GET['page'] ?? 1;
        $perPage = 12;
        $categoryId = $_GET['category_id'] ?? null;
        $search = $_GET['search'] ?? '';
        
        $productsData = $productModel->getPaginatedProducts($page, $perPage, $categoryId, $search);
        $categories = $categoryModel->getAllActive();
        
        $this->render('admin/products/list', [
            'products' => $productsData['products'],
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $productsData['total_pages'],
                'total' => $productsData['total']
            ],
            'categories' => $categories,
            'current_category' => $categoryId,
            'search' => $search
        ]);
    }
    
    public function createProduct()
    {
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllActive();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateProductData($_POST);
            
            if (empty($errors)) {
                $productModel = new ProductModel();
                $productId = $productModel->create($this->prepareProductData($_POST));
                
                if ($productId) {
                    // Обработка загрузки основного изображения
                    if (!empty($_FILES['image']['name'])) {
                        $upload = new Upload();
                        $imagePath = $upload->handleImageUpload($_FILES['image'], 'products');
                        if ($imagePath) {
                            $productModel->update($productId, ['image' => $imagePath]);
                        }
                    }
                    
                    // Обработка загрузки фонового изображения
                    if (!empty($_FILES['background_image']['name'])) {
                        $upload = new Upload();
                        $bgImagePath = $upload->handleImageUpload($_FILES['background_image'], 'products/backgrounds');
                        if ($bgImagePath) {
                            $productModel->update($productId, [
                                'background_type' => 'image',
                                'background_value' => $bgImagePath
                            ]);
                        }
                    }
                    
                    $this->session->set('success', 'Товар успешно создан');
                    $this->redirect('/admin/products');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/products/edit', [
            'product' => null,
            'categories' => $categories
        ]);
    }
    
    public function editProduct($id)
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        
        $product = $productModel->find($id);
        $categories = $categoryModel->getAllActive();
        
        if (!$product) {
            $this->session->set('errors', ['Товар не найден']);
            $this->redirect('/admin/products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateProductData($_POST);
            
            if (empty($errors)) {
                $updateData = $this->prepareProductData($_POST);
                
                // Обработка загрузки основного изображения
                if (!empty($_FILES['image']['name'])) {
                    $upload = new Upload();
                    $imagePath = $upload->handleImageUpload($_FILES['image'], 'products');
                    if ($imagePath) {
                        $updateData['image'] = $imagePath;
                        // Удаляем старое изображение
                        if ($product['image']) {
                            $this->deleteFile($product['image']);
                        }
                    }
                }
                
                // Обработка загрузки фонового изображения
                if (!empty($_FILES['background_image']['name'])) {
                    $upload = new Upload();
                    $bgImagePath = $upload->handleImageUpload($_FILES['background_image'], 'products/backgrounds');
                    if ($bgImagePath) {
                        $updateData['background_type'] = 'image';
                        $updateData['background_value'] = $bgImagePath;
                        // Удаляем старое изображение
                        if ($product['background_type'] === 'image' && $product['background_value']) {
                            $this->deleteFile($product['background_value']);
                        }
                    }
                }
                
                if ($productModel->update($id, $updateData)) {
                    $this->session->set('success', 'Товар успешно обновлен');
                    $this->redirect('/admin/products');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/products/edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }
    
    public function deleteProduct($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);
        
        if ($product) {
            // Удаляем изображения
            if ($product['image']) {
                $this->deleteFile($product['image']);
            }
            if ($product['background_type'] === 'image' && $product['background_value']) {
                $this->deleteFile($product['background_value']);
            }
            
            if ($productModel->delete($id)) {
                $this->session->set('success', 'Товар успешно удален');
            } else {
                $this->session->set('errors', ['Ошибка при удалении товара']);
            }
        } else {
            $this->session->set('errors', ['Товар не найден']);
        }
        
        $this->redirect('/admin/products');
    }
    
    public function toggleProduct($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);
        
        if ($product) {
            $newStatus = $product['is_active'] ? 0 : 1;
            $productModel->update($id, ['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'активирован' : 'деактивирован';
            $this->session->set('success', "Товар успешно {$statusText}");
        } else {
            $this->session->set('errors', ['Товар не найден']);
        }
        
        $this->redirect('/admin/products');
    }
    
    private function validateProductData($data)
    {
        $rules = [
            'name' => 'required|max:255',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric|min:0',
            'background_type' => 'required|in:color,gradient,image'
        ];
        
        return $this->validator->validate($data, $rules);
    }
    
    private function prepareProductData($data)
    {
        return [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'category_id' => intval($data['category_id']),
            'price' => floatval($data['price']),
            'background_type' => $data['background_type'],
            'background_value' => $data['background_value'] ?? '',
            'text_color' => $data['text_color'] ?? '',
            'font_family' => $data['font_family'] ?? '',
            'is_active' => isset($data['is_active']) ? 1 : 0
        ];
    }
    
    // ==================== СТРАНИЦЫ ====================
    
    public function pages()
    {
        $pageModel = new PageModel();
        $pages = $pageModel->getAll();
        
        $this->render('admin/pages/list', ['pages' => $pages]);
    }
    
    public function createPage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validatePageData($_POST);
            
            if (empty($errors)) {
                $pageModel = new PageModel();
                $pageId = $pageModel->create($this->preparePageData($_POST));
                
                if ($pageId) {
                    $this->session->set('success', 'Страница успешно создана');
                    $this->redirect('/admin/pages');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/pages/edit', ['page' => null]);
    }
    
    public function editPage($id)
    {
        $pageModel = new PageModel();
        $page = $pageModel->find($id);
        
        if (!$page) {
            $this->session->set('errors', ['Страница не найдена']);
            $this->redirect('/admin/pages');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validatePageData($_POST);
            
            if (empty($errors)) {
                if ($pageModel->update($id, $this->preparePageData($_POST))) {
                    $this->session->set('success', 'Страница успешно обновлена');
                    $this->redirect('/admin/pages');
                }
            } else {
                $this->session->set('errors', $errors);
            }
        }
        
        $this->render('admin/pages/edit', ['page' => $page]);
    }
    
    public function deletePage($id)
    {
        $pageModel = new PageModel();
        $page = $pageModel->find($id);
        
        // Запрещаем удаление системных страниц
        $systemPages = ['about', 'contacts', 'guarantee', 'delivery', 'faq'];
        if (in_array($page['slug'], $systemPages)) {
            $this->session->set('errors', ['Нельзя удалить системную страницу']);
            $this->redirect('/admin/pages');
        }
        
        if ($pageModel->delete($id)) {
            $this->session->set('success', 'Страница успешно удалена');
        } else {
            $this->session->set('errors', ['Ошибка при удалении страницы']);
        }
        
        $this->redirect('/admin/pages');
    }
    
    public function togglePage($id)
    {
        $pageModel = new PageModel();
        $page = $pageModel->find($id);
        
        if ($page) {
            $newStatus = $page['is_active'] ? 0 : 1;
            $pageModel->update($id, ['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'активирована' : 'деактивирована';
            $this->session->set('success', "Страница успешно {$statusText}");
        } else {
            $this->session->set('errors', ['Страница не найдена']);
        }
        
        $this->redirect('/admin/pages');
    }
    
    private function validatePageData($data)
    {
        $rules = [
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|max:100',
            'content' => 'required'
        ];
        
        return $this->validator->validate($data, $rules);
    }
    
    private function preparePageData($data)
    {
        return [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'meta_title' => $data['meta_title'] ?? '',
            'meta_description' => $data['meta_description'] ?? '',
            'is_active' => isset($data['is_active']) ? 1 : 0
        ];
    }
    
    // ==================== ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ ====================
    
    private function deleteFile($filePath)
    {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/uploads/' . $filePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    
    // АУТЕНТИФИКАЦИЯ
    public function login()
    {
        if ($this->auth->isLoggedIn()) {
            $this->redirect('/admin/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($this->auth->login($username, $password)) {
                $this->redirect('/admin/dashboard');
            } else {
                $this->session->set('errors', ['Неверные учетные данные']);
            }
        }
        
        $this->render('admin/login');
    }
    
    public function logout()
    {
        $this->auth->logout();
        $this->redirect('/admin/login');
    }
}
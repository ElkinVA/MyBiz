<?php

class ApiController extends Controller {
    
    public function products() {
        try {
            $page = $_GET['page'] ?? 1;
            $perPage = 12;
            
            $productModel = new ProductModel();
            $products = $productModel->getActiveProducts($page, $perPage);
            
            $this->json([
                'success' => true,
                'products' => $products,
                'page' => $page
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'error' => 'Ошибка загрузки товаров'
            ], 500);
        }
    }
    
    public function search() {
        try {
            $query = $_GET['q'] ?? '';
            $page = $_GET['page'] ?? 1;
            
            if (empty($query)) {
                $this->json([
                    'success' => false,
                    'error' => 'Пустой поисковый запрос'
                ], 400);
                return;
            }
            
            $productModel = new ProductModel();
            $products = $productModel->searchProducts($query, $page);
            
            $this->json([
                'success' => true,
                'products' => $products,
                'query' => $query,
                'page' => $page
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'error' => 'Ошибка поиска'
            ], 500);
        }
    }
}
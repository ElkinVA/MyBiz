<?php

class CategoryModel extends BaseModel {
    protected $table = 'categories';

    public function __construct() {
        parent::__construct();
    }

    public function getActiveCategories() {
        return $this->findAll(['is_active' => 1], 'sort_order ASC');
    }

    public function getCategoryWithProducts($categoryId) {
        $category = $this->find($categoryId);
        
        if ($category) {
            $productModel = new ProductModel();
            $category['products'] = $productModel->getByCategory($categoryId);
        }
        
        return $category;
    }
    public function getAllWithProductCount()
{
    $sql = "SELECT c.*, COUNT(p.id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            GROUP BY c.id 
            ORDER BY c.sort_order ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAllActive() {
        $sql = "SELECT * FROM categories 
                WHERE is_active = 1 
                ORDER BY sort_order ASC, name ASC";
        
        return $this->db->select($sql);
    }

}
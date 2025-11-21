<?php

namespace App\Models;

class ProductModel extends BaseModel
{
    protected $table = 'products';
    
    public function getPaginatedProducts($page = 1, $limit = 10, $categoryId = null)
    {
        $offset = ($page - 1) * $limit;
        
        if ($categoryId) {
            $query = "SELECT * FROM {$this->table} WHERE category_id = ? 
                     ORDER BY id 
                     OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$categoryId, $offset, $limit]);
        } else {
            $query = "SELECT * FROM {$this->table} 
                     ORDER BY id 
                     OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$offset, $limit]);
        }
        
        return $stmt->fetchAll();
    }
    
    public function getActiveCount()
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'active'";
        $result = $this->db->query($query)->fetch();
        return $result['count'];
    }
    
    public function getPublishedCount()
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE is_published = 1";
        $result = $this->db->query($query)->fetch();
        return $result['count'];
    }
     public function getAll($limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = ?";
        
        $params = [STATUS_ACTIVE];
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
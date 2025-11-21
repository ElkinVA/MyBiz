<?php

class PageModel extends BaseModel {
    protected $table = 'pages';

    public function __construct() {
        parent::__construct();
    }

    public function getBySlug($slug)
{
    try {
        $sql = "SELECT * FROM pages WHERE slug = :slug AND is_active = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("PageModel getBySlug error: " . $e->getMessage());
        return false;
    }
}

    public function getActivePages() {
        return $this->findAll(['is_active' => 1], 'title ASC');
    }
}
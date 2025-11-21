<?php

class SliderModel extends BaseModel {
    protected $table = 'sliders';

    public function __construct() {
        parent::__construct();
    }

    public function getActiveSliders($position = null) {
        $conditions = ['is_active' => 1];
        
        if ($position) {
            $conditions['slider_position'] = $position;
        }
        
        return $this->findAll($conditions, 'sort_order ASC');
    }

    public function getByPosition($position) {
        return $this->findAll([
            'slider_position' => $position,
            'is_active' => 1
        ], 'sort_order ASC');
    }
    public function getActiveByPosition($position) {
        $sql = "SELECT * FROM sliders 
                WHERE slider_position = :position 
                AND is_active = 1 
                ORDER BY sort_order ASC";
        
        return $this->db->select($sql, ['position' => $position]);
    }
}
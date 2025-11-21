<?php

class SettingsModel extends BaseModel {
    protected $table = 'settings';

    public function __construct() {
        parent::__construct();
    }

    public function getByKey($key) {
        return $this->findOneBy(['setting_key' => $key]);
    }

    public function updateByKey($key, $value) {
        $existing = $this->getByKey($key);
        
        if ($existing) {
            return $this->update($existing['id'], [
                'setting_value' => $value,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->create([
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_type' => 'string'
            ]);
        }
    }

    public function getAllSettings() {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    }
    public function getAllGrouped()
{
    $settings = $this->getAll();
    $grouped = [];
    
    foreach ($settings as $setting) {
        $group = explode('_', $setting['setting_key'])[0];
        $grouped[$group][] = $setting;
    }
    
    return $grouped;
}

public function getByGroup($group)
{
    $sql = "SELECT * FROM settings WHERE setting_key LIKE ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$group . '_%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateSetting($key, $value)
{
    return $this->updateByField('setting_key', $key, ['setting_value' => $value]);
}
}
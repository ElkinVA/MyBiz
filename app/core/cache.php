<?php
class Cache
{
    private $cacheDir;
    private $defaultTtl;

    public function __construct($cacheDir = null, $defaultTtl = 3600)
    {
        $this->cacheDir = $cacheDir ?: APP_ROOT . '/storage/cache';
        $this->defaultTtl = $defaultTtl;
        
        // Создаем директорию кэша если не существует
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function get($key)
    {
        $filename = $this->getFilename($key);
        
        if (!file_exists($filename)) {
            return null;
        }

        $data = unserialize(file_get_contents($filename));
        
        if ($data['expires'] < time()) {
            $this->delete($key);
            return null;
        }

        return $data['value'];
    }

    public function set($key, $value, $ttl = null)
    {
        $ttl = $ttl ?: $this->defaultTtl;
        $filename = $this->getFilename($key);
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl,
            'created' => time()
        ];

        return file_put_contents($filename, serialize($data)) !== false;
    }

    public function delete($key)
    {
        $filename = $this->getFilename($key);
        
        if (file_exists($filename)) {
            return unlink($filename);
        }
        
        return true;
    }

    public function clear()
    {
        $files = glob($this->cacheDir . '/*.cache');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        return true;
    }

    public function clearExpired()
    {
        $files = glob($this->cacheDir . '/*.cache');
        $now = time();
        $cleared = 0;
        
        foreach ($files as $file) {
            $data = unserialize(file_get_contents($file));
            if ($data['expires'] < $now) {
                unlink($file);
                $cleared++;
            }
        }
        
        return $cleared;
    }

    private function getFilename($key)
    {
        $safeKey = preg_replace('/[^a-zA-Z0-9_-]/', '_', $key);
        return $this->cacheDir . '/' . $safeKey . '.cache';
    }

    public function getStats()
    {
        $files = glob($this->cacheDir . '/*.cache');
        $totalSize = 0;
        $expiredCount = 0;
        $now = time();
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
            $data = unserialize(file_get_contents($file));
            if ($data['expires'] < $now) {
                $expiredCount++;
            }
        }
        
        return [
            'total_files' => count($files),
            'total_size' => $totalSize,
            'expired_files' => $expiredCount
        ];
    }
}
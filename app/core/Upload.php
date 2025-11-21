<?php
namespace App\Core;

class Upload {
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $maxSize = 5 * 1024 * 1024; // 5MB
    private $uploadPath;

    public function __construct($uploadPath = null) {
        $this->uploadPath = $uploadPath ?: $_SERVER['DOCUMENT_ROOT'] . '/public/assets/images/uploads/';
        
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function image($file, $subfolder = '') {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Ошибка загрузки файла: ' . $file['error']);
        }

        // Проверка типа файла
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedTypes)) {
            throw new \Exception('Недопустимый тип файла. Разрешены только JPEG, PNG, GIF, WebP');
        }

        // Проверка размера
        if ($file['size'] > $this->maxSize) {
            throw new \Exception('Размер файла превышает допустимый лимит 5MB');
        }

        // Создание подпапки если нужно
        $targetPath = $this->uploadPath . $subfolder;
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        // Генерация уникального имени
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $targetPath . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new \Exception('Не удалось сохранить файл');
        }

        return $filename;
    }

    public function delete($filename, $subfolder = '') {
        $filepath = $this->uploadPath . $subfolder . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
?>
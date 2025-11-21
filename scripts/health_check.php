<?php
/**
 * Health Check Endpoint for Monitoring
 */

header('Content-Type: application/json');

$health = [
    'status' => 'healthy',
    'timestamp' => date('c'),
    'services' => []
];

// Check database connectivity
try {
    $config = require '../app/config/config.php';
    $dbConfig = $config['database'];
    
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}",
        $dbConfig['user'],
        $dbConfig['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    // Test query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    
    $health['services']['database'] = [
        'status' => 'healthy',
        'response_time' => round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3)
    ];
    
} catch (Exception $e) {
    $health['services']['database'] = [
        'status' => 'unhealthy',
        'error' => $e->getMessage()
    ];
    $health['status'] = 'unhealthy';
}

// Check file system permissions
$requiredDirs = [
    '../storage/logs' => 'writable',
    '../storage/cache' => 'writable',
    '../public/assets/images/uploads' => 'writable'
];

foreach ($requiredDirs as $dir => $requirement) {
    if (is_writable($dir)) {
        $health['services']["directory_$dir"] = [
            'status' => 'healthy',
            'permission' => 'writable'
        ];
    } else {
        $health['services']["directory_$dir"] = [
            'status' => 'unhealthy',
            'permission' => 'readonly',
            'error' => "Directory $dir is not writable"
        ];
        $health['status'] = 'unhealthy';
    }
}

// Check disk space
$diskFree = disk_free_space('/');
$diskTotal = disk_total_space('/');
$diskUsage = round(($diskTotal - $diskFree) / $diskTotal * 100, 2);

$health['system'] = [
    'disk_usage_percent' => $diskUsage,
    'memory_usage' => memory_get_usage(true),
    'memory_peak_usage' => memory_get_peak_usage(true)
];

if ($diskUsage > 90) {
    $health['status'] = 'warning';
    $health['system']['disk_warning'] = 'Disk usage is above 90%';
}

http_response_code($health['status'] === 'healthy' ? 200 : 503);
echo json_encode($health, JSON_PRETTY_PRINT);
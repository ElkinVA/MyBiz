<?php
/**
 * Monitoring Script for Production Environment
 */

class ProductionMonitor
{
    private $logFile;
    private $alertThresholds;
    
    public function __construct()
    {
        $this->logFile = dirname(__DIR__) . '/storage/logs/monitor.log';
        $this->alertThresholds = [
            'disk_usage' => 90,
            'memory_usage' => 85,
            'response_time' => 5.0,
            'error_rate' => 5
        ];
    }
    
    public function checkSystemHealth()
    {
        $checks = [];
        
        // Disk Usage
        $diskUsage = $this->getDiskUsage();
        $checks['disk_usage'] = [
            'value' => $diskUsage,
            'status' => $diskUsage < $this->alertThresholds['disk_usage'] ? 'OK' : 'CRITICAL',
            'threshold' => $this->alertThresholds['disk_usage']
        ];
        
        // Memory Usage
        $memoryUsage = $this->getMemoryUsage();
        $checks['memory_usage'] = [
            'value' => $memoryUsage,
            'status' => $memoryUsage < $this->alertThresholds['memory_usage'] ? 'OK' : 'CRITICAL',
            'threshold' => $this->alertThresholds['memory_usage']
        ];
        
        // Database Connectivity
        $dbStatus = $this->checkDatabase();
        $checks['database'] = [
            'status' => $dbStatus ? 'OK' : 'CRITICAL'
        ];
        
        // Application Response Time
        $responseTime = $this->checkResponseTime();
        $checks['response_time'] = [
            'value' => $responseTime,
            'status' => $responseTime < $this->alertThresholds['response_time'] ? 'OK' : 'WARNING',
            'threshold' => $this->alertThresholds['response_time']
        ];
        
        return $checks;
    }
    
    private function getDiskUsage()
    {
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        return round(100 - ($diskFree / $diskTotal * 100), 2);
    }
    
    private function getMemoryUsage()
    {
        $memoryInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $memoryInfo, $total);
        preg_match('/MemAvailable:\s+(\d+)/', $memoryInfo, $available);
        
        if ($total[1] > 0) {
            return round(100 - ($available[1] / $total[1] * 100), 2);
        }
        
        return 0;
    }
    
    private function checkDatabase()
    {
        try {
            $config = require dirname(__DIR__) . '/app/config/config.php';
            $dbConfig = $config['database'];
            
            $pdo = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']}",
                $dbConfig['user'],
                $dbConfig['pass']
            );
            
            $stmt = $pdo->query("SELECT 1");
            return $stmt !== false;
        } catch (Exception $e) {
            $this->logError("Database check failed: " . $e->getMessage());
            return false;
        }
    }
    
    private function checkResponseTime()
    {
        $start = microtime(true);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://mybiz-shop.ru/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_NOBODY => true
        ]);
        
        curl_exec($ch);
        $responseTime = microtime(true) - $start;
        curl_close($ch);
        
        return round($responseTime, 3);
    }
    
    public function logError($message)
    {
        $logEntry = date('Y-m-d H:i:s') . " - ERROR: " . $message . PHP_EOL;
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    public function generateReport()
    {
        $health = $this->checkSystemHealth();
        $criticalIssues = array_filter($health, function($check) {
            return $check['status'] === 'CRITICAL';
        });
        
        $report = [
            'timestamp' => date('c'),
            'overall_status' => empty($criticalIssues) ? 'HEALTHY' : 'CRITICAL',
            'checks' => $health,
            'critical_issues' => count($criticalIssues)
        ];
        
        return $report;
    }
}

// Run monitoring if executed directly
if (php_sapi_name() === 'cli') {
    $monitor = new ProductionMonitor();
    $report = $monitor->generateReport();
    
    echo "MyBiz Production Health Report\n";
    echo "==============================\n";
    echo "Timestamp: " . $report['timestamp'] . "\n";
    echo "Overall Status: " . $report['overall_status'] . "\n";
    echo "Critical Issues: " . $report['critical_issues'] . "\n\n";
    
    foreach ($report['checks'] as $checkName => $check) {
        echo str_pad($checkName, 15) . " : " . $check['status'];
        if (isset($check['value'])) {
            echo " (" . $check['value'] . ")";
        }
        echo "\n";
    }
}
<?php

class Environment
{
    private static $config = [];
    private static $loaded = false;

    public static function load($envFile = '.env')
    {
        if (self::$loaded) {
            return;
        }

        $envPath = dirname(__DIR__, 2) . '/' . $envFile;
        
        if (!file_exists($envPath)) {
            throw new Exception("Environment file not found: " . $envPath);
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove quotes if present
            $value = trim($value, '"\'');
            
            self::$config[$name] = $value;
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }

        self::$loaded = true;
    }

    public static function get($key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$config[$key] ?? $default;
    }

    public static function isProduction()
    {
        return self::get('APP_ENV') === 'production';
    }

    public static function isDebug()
    {
        return self::get('APP_DEBUG') === 'true';
    }
}

// Auto-load environment on include
Environment::load();
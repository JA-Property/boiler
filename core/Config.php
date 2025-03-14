<?php
namespace Core;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use RuntimeException;
use Setup\Models\EnvModel;

class Config
{
    private static array $config = [];
    private static string $envPath = '';
    private static ?EnvModel $envModel = null;

    public static function validateEnvVars(array $keys): array {
        $missing = [];
        $env = self::$envModel->readEnv();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $env)) {  // Ensures the key exists even if empty.
                $missing[] = $key;
            }
        }
        return $missing;
    }
    
    public static function load(): void
    {
        // Ensure session is started for error handling.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    
        // Determine the expected .env file location.
        self::$envPath = dirname(__DIR__) . '/.env';
    
        if (!file_exists(self::$envPath)) {
            // Search for any misplaced .env file.
            $foundEnv = self::searchEnvFile(dirname(__DIR__));
            if ($foundEnv) {
                $_SESSION['setup_error'] = [
                    'type'    => 'misplaced_env',
                    'message' => "Found .env file in wrong location: " . $foundEnv
                ];
                header("Location: /setup/missing_env");
                exit;
            }
    
            // If .env is completely missing.
            $_SESSION['setup_error'] = [
                'type'    => 'missing_env',
                'message' => ".env file is missing."
            ];
            header("Location: /setup/missing_env");
            exit;
        }
    
        // Load environment variables from the .env file.
        try {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__));
            $dotenv->load();
        } catch (InvalidPathException $e) {
            $_SESSION['setup_error'] = [
                'type'    => 'invalid_env',
                'message' => $e->getMessage()
            ];
            header("Location: /setup/missing_env");
            exit;
        }
    
        // Initialize EnvModel if not already done.
        if (self::$envModel === null) {
            self::$envModel = new EnvModel();
        }
    
        // Validate required environment variables.
        $missingVars = self::validateEnvVars([
            'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS',
            'APP_ENV', 'APP_DEBUG'
        ]);
    
        if (!empty($missingVars)) {
            // Instead of showing the missing_env view, redirect to the DB update page.
            $_SESSION['dbErrorMessage'] = "Missing environment keys: " . implode(', ', $missingVars);
            header("Location: /setup/database");
            exit;
        }
    
        // If everything is in place, load configuration files.
        self::$config['database'] = require dirname(__DIR__) . '/config/database.php';
        self::$config['app']      = require dirname(__DIR__) . '/config/app.php';
    }
    
    private static function searchEnvFile(string $dir): ?string
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
    
        foreach ($iterator as $file) {
            // Skip checking inside the vendor directory.
            if (strpos($file->getPath(), 'vendor') !== false) {
                continue;
            }
    
            if ($file->getFilename() === '.env') {
                return $file->getPathname();
            }
        }
        return null;
    }
    
    public static function get(string $key): mixed
    {
        if (!isset(self::$config[$key])) {
            throw new RuntimeException("Configuration key '$key' not found.");
        }
        return self::$config[$key];
    }
}

<?php
/**
 * /public/setup/index.php
 */

require_once __DIR__ . '/Models/EnvModel.php';
require_once __DIR__ . '/Models/DatabaseModel.php';
require_once __DIR__ . '/Controllers/SetupController.php';

use Setup\Controllers\SetupController;

// Start a session if you want to store "error" messages without showing them in the URL
session_start();

// 1) Grab the PATH_INFO (or fallback if none).
$pathInfo = $_SERVER['PATH_INFO'] ?? '/';
$pathInfo = trim($pathInfo, '/');   // e.g. "database" or "" or "db_tables"

// 2) Break into segments if you need multiple. For this wizard, usually we only need the first:
$segments = explode('/', $pathInfo);
$step = $segments[0] ?? '';   // e.g. 'database', 'db_tables', etc.

// If there's no segment at all, default to 'database'
if ($step === '') {
    $step = 'database';
}

// If you also need an error-check route, you could do something like
// if ($step === 'missing_env') { ... } 
// but let's skip that for now and rely on session-based errors if needed.

$controller = new SetupController();

// Decide which step method to call
switch ($step) {
    case 'missing_env':
        $controller->missingEnv();
        break;
    case 'database':
        $controller->database();
        break;
    case 'db_tables':
        $controller->dbTables();
        break;
    case 'app_settings':
        $controller->appSettings();
        break;
    case 'admin_account':
        $controller->adminAccount();
        break;
    case 'finish':
        $controller->finish();
        break;
    case 'test_connection':  // New endpoint for AJAX testing
        $controller->testConnection();
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo "Not found";
        exit;
}

<?php
/**
 * /public/setup/Controllers/SetupController.php
 */
namespace Setup\Controllers;

use Setup\Models\EnvModel;
use Setup\Models\DatabaseModel;

class SetupController
{
    protected $envModel;
    protected $dbModel;

    public function __construct()
    {
        $this->envModel = new EnvModel();
        $this->dbModel  = new DatabaseModel();
    }
    
    public function missingEnv()
    {
        // Ensure session is started.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the .env file path from the EnvModel.
            $envPath = $this->envModel->getEnvPath();
            // Define some default content for the new .env file.
            $defaultContent = "APP_ENV=development\nAPP_DEBUG=true\n";
            // Attempt to create the file.
            if (file_put_contents($envPath, $defaultContent) !== false && file_exists($envPath)) {
                // If creation succeeded, redirect to the database setup step.
                header("Location: /setup/database");
                exit;
            } else {
                // If creation fails, store an error message in the session.
                $_SESSION['setup_error'] = [
                    'type'    => 'env_creation_failed',
                    'message' => "Failed to create .env file. Please check folder permissions."
                ];
            }
        }
        $this->render('missing_env');
    }
    
    public function testConnection()
{
    // Ensure the session is started.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Only accept POST requests.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("HTTP/1.1 405 Method Not Allowed");
        exit;
    }
    
    // Retrieve JSON payload from the request.
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);
    
    // Extract parameters with defaults.
    $host = trim($data['DB_HOST'] ?? '');
    $dbName = trim($data['DB_NAME'] ?? '');
    $user = trim($data['DB_USER'] ?? '');
    $pass = $data['DB_PASS'] ?? '';
    $allowEmptyPass = isset($data['allow_empty_password']) && $data['allow_empty_password'];
    $createDbWanted = isset($data['create_db_if_missing']) && $data['create_db_if_missing'];
    
    // Validate basic input.
    if (empty($pass) && !$allowEmptyPass) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'DB_PASS cannot be blank unless allowed.']);
        exit;
    }
    if (empty($dbName)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'DB_NAME cannot be empty.']);
        exit;
    }
    
    // Use your existing DatabaseModel to check (and optionally create) the DB.
    $error = $this->dbModel->checkOrCreateDb($host, $dbName, $user, $pass, $createDbWanted);
    
    header('Content-Type: application/json');
    if (!empty($error)) {
        echo json_encode(['success' => false, 'message' => $error]);
    } else {
        echo json_encode(['success' => true]);
    }
    exit;
}

    public function database()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $host            = $_POST['DB_HOST'] ?? '';
            $dbName          = $_POST['DB_NAME'] ?? '';
            $user            = $_POST['DB_USER'] ?? '';
            $pass            = $_POST['DB_PASS'] ?? '';
            $allowEmptyPass  = isset($_POST['allow_empty_password']);
            $createDbWanted  = isset($_POST['create_db_if_missing']);
    
            // Validation: check DB password
            if (empty($pass) && !$allowEmptyPass) {
                $_SESSION['dbPassError'] = true;
                header("Location: /setup/database");
                exit;
            }
            // Validation: DB name must not be empty
            if (!$dbName) {
                $_SESSION['dbErrorMessage'] = "DB_NAME cannot be empty";
                header("Location: /setup/database");
                exit;
            }
    
            // Check (and optionally create) the database
            $dbError = $this->dbModel->checkOrCreateDb($host, $dbName, $user, $pass, $createDbWanted);
            if ($dbError) {
                $_SESSION['dbErrorMessage'] = $dbError;
                header("Location: /setup/database");
                exit;
            }
    
            // Write .env file
            $this->envModel->updateEnv([
                'DB_HOST' => $host,
                'DB_NAME' => $dbName,
                'DB_USER' => $user,
                'DB_PASS' => $pass
                // plus any others like APP_ENV, APP_DEBUG if needed
            ], $allowEmptyPass);
    
            // On success, go to next step
            header("Location: /setup/db_tables");
            exit;
        }
    
        // For GET: load any existing .env values and error messages from session
        $existing        = $this->envModel->readEnv();
        $dbErrorMessage  = $_SESSION['dbErrorMessage'] ?? '';
        $dbPassError     = $_SESSION['dbPassError'] ?? false;
        unset($_SESSION['dbErrorMessage'], $_SESSION['dbPassError']);
    
        // Render the "database" view
        $this->render('database', compact('existing', 'dbErrorMessage', 'dbPassError'));
    }
    
    public function dbTables()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // e.g. run migrations, then move to app settings
            header("Location: /setup/app_settings");
            exit;
        }
    
        $this->render('db_tables');
    }
    
    public function appSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $envData = [
                'APP_ENV'   => $_POST['APP_ENV']   ?? 'production',
                'APP_DEBUG' => $_POST['APP_DEBUG'] ?? 'false'
            ];
            $this->envModel->updateEnv($envData);
    
            header("Location: /setup/admin_account");
            exit;
        }
    
        $existing = $this->envModel->readEnv();
        $this->render('app_settings', compact('existing'));
    }
    
    public function adminAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // e.g. create admin user in the database
            header("Location: /setup/finish");
            exit;
        }
        $this->render('admin_account');
    }
    
    public function finish()
    {
        $this->render('finish');
    }
    
    protected function render($viewName, array $data = [])
    {
        // Ensure session is started so views can access session data if needed.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Add the current step to the data array for the view.
        $step = $_SERVER['PATH_INFO'] ?? '';
        $step = trim($step, '/');
        if ($step === '') {
            $step = 'database';
        }
        $data['step'] = $step;
    
        // Make the data variables available to the view.
        extract($data);
    
        // Capture the step-specific view content.
        ob_start();
        require __DIR__ . '/../Views/' . $viewName . '.php';
        $content = ob_get_clean();
    
        // Include the master layout which uses $content.
        require __DIR__ . '/../Views/_layout.php';
    }
}

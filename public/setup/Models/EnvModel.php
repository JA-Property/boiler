<?php
namespace Setup\Models;

class EnvModel
{
    protected $envPath;

    public function __construct()
    {
        // Adjust the path if your .env file is two levels above the current directory.
        $this->envPath = realpath(__DIR__ . '/../../../') . '/.env';
    }

    public function readEnv()
    {
        $data = [];
        if (file_exists($this->envPath)) {
            $lines = file($this->envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    [$key, $val] = explode('=', $line, 2);
                    $data[trim($key)] = trim($val);
                }
            }
        }
        return $data;
    }

    public function updateEnv(array $newValues, bool $allowEmptyPass = false)
    {
        $existing = $this->readEnv();

        // Merge new values with existing values.
        $merged = array_merge($existing, $newValues);

        // Build .env file content.
        $content = 'ALLOW_EMPTY_DB_PASS=' . ($allowEmptyPass ? 'true' : 'false') . "\n";
        foreach ($merged as $key => $val) {
            if ($key === 'ALLOW_EMPTY_DB_PASS') continue; // skip duplicate key
            $content .= $key . '=' . $val . "\n";
        }

        file_put_contents($this->envPath, $content);
    }
    
    // Add a public getter for the .env file path.
    public function getEnvPath()
    {
        return $this->envPath;
    }
}

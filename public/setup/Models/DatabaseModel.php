<?php
namespace Setup\Models;

use PDO;
use PDOException;

/**
 * Handles checking (and optionally creating) a MySQL database,
 * and verifying the final connection.
 */
class DatabaseModel
{
    /**
     * Check if a database exists. If not, optionally create it. Then verify we can connect to it.
     *
     * @param string $host           e.g. "localhost"
     * @param string $dbName         e.g. "my_database"
     * @param string $user           DB user
     * @param string $pass           DB pass
     * @param bool   $createDbWanted Did user want to create DB if missing?
     *
     * @return string  Empty if success, or an error message if failed.
     */
    public function checkOrCreateDb(
        string $host,
        string $dbName,
        string $user,
        string $pass,
        bool   $createDbWanted
    ): string
    {
        // 1) Connect to the MySQL server (without a database) so we can query INFORMATION_SCHEMA.
        try {
            $pdoServer = new PDO(
                "mysql:host={$host};charset=utf8mb4",
                $user,
                $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $ex) {
            return "Failed to connect to MySQL at host '{$host}' with user '{$user}': " . $ex->getMessage();
        }
    
        // 2) Check if $dbName exists.
        try {
            $stmt = $pdoServer->prepare(
                "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?"
            );
            $stmt->execute([$dbName]);
            $dbExists = (bool) $stmt->fetch();
        } catch (PDOException $ex) {
            return "Failed to check database existence: " . $ex->getMessage();
        }
    
        // 3) If the database does not exist, create it if desired.
        if (!$dbExists) {
            if ($createDbWanted) {
                try {
                    $pdoServer->exec("CREATE DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                } catch (PDOException $ex) {
                    return "Database '{$dbName}' did not exist, and creation failed: " . $ex->getMessage();
                }
            } else {
                return "Database '{$dbName}' does not exist (and creation not selected).";
            }
        }
    
        // 4) Verify connection using the database name.
        try {
            $pdoDb = new PDO(
                "mysql:host={$host};dbname={$dbName};charset=utf8mb4",
                $user,
                $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $ex) {
            return "Could not connect to '{$dbName}' after creation: " . $ex->getMessage();
        }
    
        // If no errors occurred, return an empty string.
        return '';
    }
}

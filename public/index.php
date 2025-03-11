<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/core/Config.php';

use Core\Config;

try {
    // Load configuration
    Config::load();

    // Example usage
    $dbConfig = Config::get('database');
    $appConfig = Config::get('app');

} catch (RuntimeException $e) {
    die($e->getMessage()); // Stop execution immediately
}

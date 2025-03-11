<?php

return [
    'host' => $_ENV['DB_HOST'], // Will throw an error if missing
    'name' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'pass' => $_ENV['DB_PASS'],
    'charset' => 'utf8mb4'
];

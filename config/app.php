<?php

return [
    'environment' => $_ENV['APP_ENV'],
    'debug' => filter_var($_ENV['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN)
];

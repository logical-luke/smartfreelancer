<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context) {
    if ($_ENV['APP_ENV'] === 'dev') {
        header('Access-Control-Allow-Origin: http://localhost:5173');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range, Authorization');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 204 OK");
            die();
        }
    }

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};


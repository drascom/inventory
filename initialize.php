<?php
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception(".env file is missing");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

loadEnv(__DIR__ . '/.env');

// Environment Detection
$is_development = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1', 'localhost:8888']);

if ($is_development) {
    // Local Development Settings
    if (!defined('DB_SERVER'))
        define('DB_SERVER', getenv('DEV_DB_SERVER'));
    if (!defined('DB_USERNAME'))
        define('DB_USERNAME', getenv('DEV_DB_USERNAME'));
    if (!defined('DB_PASSWORD'))
        define('DB_PASSWORD', getenv('DEV_DB_PASSWORD'));
    if (!defined('DB_NAME'))
        define('DB_NAME', getenv('DEV_DB_NAME'));
    if (!defined('base_url'))
        define('base_url', getenv('DEV_BASE_URL'));

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // Production Settings
    if (!defined('DB_SERVER'))
        define('DB_SERVER', getenv('PROD_DB_SERVER'));
    if (!defined('DB_USERNAME'))
        define('DB_USERNAME', getenv('PROD_DB_USERNAME'));
    if (!defined('DB_PASSWORD'))
        define('DB_PASSWORD', getenv('PROD_DB_PASSWORD'));
    if (!defined('DB_NAME'))
        define('DB_NAME', getenv('PROD_DB_NAME'));
    if (!defined('base_url'))
        define('base_url', getenv('PROD_BASE_URL'));

    error_reporting(0);
    ini_set('display_errors', 0);
}

// Common Configuration
if (!defined('base_app'))
    define('base_app', str_replace('\\', '/', __DIR__) . '/');

// Force SSL in production
if (!$is_development && $_SERVER['HTTPS'] != 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Set timezone
date_default_timezone_set('Europe/London');

// Session security for production
if (!$is_development) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
}
?>
<?php 

// Set default timezone
date_default_timezone_set('Asia/Manila');

// App Configuration
define("APPNAME", "MicroFin");

// Database Configuration
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "db_eloq");

// Location Configuration
define('BASE_PATH', dirname(__FILE__, 3));   // D:\xampp\htdocs\eloquent_orm
define('APP_PATH', __DIR__ . '/../app');   // D:\xampp\htdocs\eloquent_orm\app

// Determine the base URL dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

$host = $_SERVER['HTTP_HOST']; 

$base_url = $protocol . $host ."/";

$entryPoint = "eloquent_orm/"; // remove entry point variable in production

define('ENTRY_POINT', $entryPoint);

define('BASE_URL', $base_url.$entryPoint); // e.g., http://localhost/eloquent_orm/

// Public URLs
define('PUBLIC_URL', $base_url . $entryPoint);
define('ASSETS', $base_url . $entryPoint . 'public');
define('API', $base_url . $entryPoint . 'api');
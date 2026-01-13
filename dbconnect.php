<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    define('DB_HOST', 'mysql.railway.internal');
    define('DB_USER', 'root');
    define('DB_NAME', 'railway');
    define('DB_PASS', 'WdtMCwmTWbaSUyJMyyYcjLOXIGcPVefv');

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
    }

    $conn->set_charset("utf8mb4");
    
?>
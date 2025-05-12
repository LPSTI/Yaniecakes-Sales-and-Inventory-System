<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if (!function_exists('logDatabaseError')) {
    function logDatabaseError($errorMessage) {
        $database_error = "Error logged at: " . date("Y-m-d H:i:s") . " - ";
        $database_error .= $errorMessage;
        $logpath = dirname(__DIR__) . "/db/log.txt";
        file_put_contents($logpath, $database_error . PHP_EOL, FILE_APPEND);
    }
}
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if ($errno === E_NOTICE || $errno === E_WARNING) {
        logDatabaseError("PHP Warning: $errstr in $errfile on line $errline");
    }
    return false;
});

function dbConnect() {
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = new mysqli("localhost", "root", "", "ca_db");

    if ($mysqli->connect_error) {
        logDatabaseError("Connection failed: " . $mysqli->connect_error);
        return false;
    }

    return $mysqli;
}

$sqlc = dbConnect();
if (!$sqlc) {
    echo "No Connection Available";
}
$error = [];
$alert = [];

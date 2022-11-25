<?php
session_start();
include "constants.php";
include "config.php";
include BASE_PATH . "vendor/autoload.php";
include BASE_PATH . "libs/lib-helpers.php";

try {
    $pdo = new PDO("mysql:dbname={$database_config->db_name};host={$database_config->host}", $database_config->user, $database_config->pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    diePage('connect Error:' . $e->getMessage() . ' - in line: ' . $e->getLine());
}

include BASE_PATH . "libs/lib-auth.php";
include BASE_PATH . "libs/lib-tasks.php";
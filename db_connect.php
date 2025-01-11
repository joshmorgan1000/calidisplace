<?php
// db_connect.php
require_once 'config.php';

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

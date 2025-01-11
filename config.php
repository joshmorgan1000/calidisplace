<?php
// config.php

// You could load from environment variables or a .env file here.
$db_host = 'localhost';
$db_name = 'u768960383_displaced';
$db_user = 'u768960383_displaced';
$db_pass = 'Displaced123*()no';

// DSN for PDO
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

// Options for PDO for better error handling & performance
$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

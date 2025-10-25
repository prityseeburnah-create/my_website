<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'car_paint_garage';

$dsn = 'mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8mb4';
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database Connection Error: Could not connect to the database '" . $db . "'. Please check if your MySQL server (XAMPP/WAMP) is running. Error: " . $e->getMessage());
}
?>

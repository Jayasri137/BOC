<?php
// includes/db.php - Database connection using PDO for Bluestone Overseas

$host = 'auth-db1278.hstgr.io';
$user = 'u287260207_new_user';
$pass = 'nwUser@>26';
$dbname = 'u287260207_overseas_newdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // Expose exact error message and stack trace for debugging
    die("Database connection failed inside db.php: " . $e->getMessage() . "\n\nStack Trace:\n" . $e->getTraceAsString());
}

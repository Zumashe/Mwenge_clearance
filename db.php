<?php
$host = 'localhost';
$dbname = 'mwecau_clearance_system';  // Hapa ni jina la database yako
$username = 'root';   // Badilisha kama una username tofauti
$password = '';       // Password ya database yako

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<?php
session_start();
require_once 'db.php';

// Check if user is admin (role_id = 10)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method!");
}

$id = $_POST['id'] ?? '';

if (!$id) {
    die("Invalid request ID");
}

$stmt = $pdo->prepare("DELETE FROM clearance_requests WHERE id = ?");
$stmt->execute([$id]);

header("Location: admin_dashboard.php");
exit;
?>

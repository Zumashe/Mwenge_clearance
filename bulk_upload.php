<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['selected_students']) || empty($_POST['selected_students'])) {
    die("No student selected.");
}

$selected_students = $_POST['selected_students'];

if (!isset($_FILES['certificate']) || $_FILES['certificate']['error'] !== UPLOAD_ERR_OK) {
    die("Please upload a certificate file.");
}

$uploadDir = __DIR__ . "/certificates/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = time() . "_" . basename($_FILES['certificate']['name']);
$targetPath = $uploadDir . $fileName;

if (!move_uploaded_file($_FILES['certificate']['tmp_name'], $targetPath)) {
    die("Failed to upload the file.");
}

try {
    $stmt = $pdo->prepare("UPDATE users SET certificate = ? WHERE id = ?");

    foreach ($selected_students as $student_id) {
        $stmt->execute([$fileName, $student_id]);
    }

    echo "Certificate uploaded successfully for selected students.";
    echo '<br><a href="admin_dashboard.php">Back to Admin Dashboard</a>';

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

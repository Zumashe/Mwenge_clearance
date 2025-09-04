<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id']) && isset($_FILES['certificate'])) {
    $student_id = $_POST['student_id'];
    $file = $_FILES['certificate'];

    $upload_dir = 'certificates/';
    $file_path = $upload_dir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $stmt = $conn->prepare("INSERT INTO clearance_certificates (student_id, certificate) VALUES (?, ?)
                                ON DUPLICATE KEY UPDATE certificate = VALUES(certificate)");
        $stmt->bind_param("is", $student_id, $file_path);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Error saving certificate.";
        }
    } else {
        echo "Upload failed.";
    }
}
?>

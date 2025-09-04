<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hostel') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $new_status = $_POST['new_status'];

    $valid_statuses = ['pending', 'approved', 'rejected'];

    if (!in_array($new_status, $valid_statuses)) {
        die('Invalid status value');
    }

    $stmt = $conn->prepare("UPDATE clearance_requests SET status_hostel = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $request_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status updated successfully.";
    } else {
        $_SESSION['message'] = "Failed to update status.";
    }
}

header('Location: hostel_dashboard.php');
exit;
?>

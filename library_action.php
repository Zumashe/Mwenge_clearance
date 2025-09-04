<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        die("Invalid action.");
    }

    $sql = "UPDATE clearance_requests SET status_library = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        header("Location: library_dashboard.php");
        exit;
    } else {
        echo "Failed to update request.";
    }
} else {
    echo "Invalid request.";
}
?>

<?php
session_start();
require_once 'db.php';
require_once 'send_email.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method!");
}

$request_id = $_POST['request_id'] ?? '';
$new_status = $_POST['new_status'] ?? '';

if (empty($request_id) || empty($new_status)) {
    die("Invalid data received!");
}

// Hizi ni columns zote za status ofices unazotaka update pamoja
$status_columns = [
    'status_registration',
    'status_finance',
    'status_dean_of_student',
    'status_mwecauso',
    'status_hod',
    'status_hostel',
    'status_head_of_faculty',
    'status_library'
];

try {
    // Anza transaction ili update zote ziwe atomic
    $pdo->beginTransaction();

    // Build SQL update set part dynamically
    $set_parts = [];
    $params = [];

    foreach ($status_columns as $col) {
        $set_parts[] = "$col = ?";
        $params[] = $new_status;
    }
    $set_sql = implode(", ", $set_parts);
    $params[] = $request_id;

    // Update all status columns in one query
    $stmt = $pdo->prepare("UPDATE clearance_requests SET $set_sql WHERE id = ?");
    $stmt->execute($params);

    // Get student info to send email
    $sql = "SELECT u.email, u.full_name, c.reg_no 
            FROM clearance_requests c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$request_id]);
    $student = $stmt->fetch();

    $pdo->commit();

    if ($student && !empty($student['email'])) {
        $subject = "Update on Your Clearance Request";
        $message = "
            <p>Dear <b>{$student['full_name']}</b>,</p>
            <p>Your clearance request for <b>Reg No: {$student['reg_no']}</b> has been updated.</p>
            <p><b>New Status for all offices:</b> $new_status</p>
            <p>Regards,<br>Mwenge Clearance System</p>
        ";
        sendNotification($student['email'], $subject, $message);
    }

    // Rudisha user dashboard
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
}

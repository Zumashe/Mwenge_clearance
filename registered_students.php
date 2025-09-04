<?php
session_start();
require_once 'db.php';

// Hakikisha admin amelogin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header('Location: login.php');
    exit;
}

$full_name = $_SESSION['full_name'] ?? 'Admin';

// Handle delete student request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student_id'])) {
    $delete_id = (int) $_POST['delete_student_id'];

    // Optional: You may want to prevent deleting yourself or check permissions here

    // Delete the student
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role_id = 1");
    $stmt->execute([':id' => $delete_id]);

    // Also delete any clearance_requests for that student to keep DB clean (optional)
    $stmt2 = $pdo->prepare("DELETE FROM clearance_requests WHERE user_id = :id");
    $stmt2->execute([':id' => $delete_id]);

    $message = "Student deleted successfully.";
}

// Pata orodha ya wanafunzi waliojisajili
$stmt = $pdo->query("SELECT id, full_name, reg_no, email FROM users WHERE role_id = 1 ORDER BY full_name ASC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Registered Students</title>
<style>
    body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; }
    .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 12px rgba(0,0,0,0.1);}
    h2 { color: #004080; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
    th { background: #004080; color: white; }
    a.button {
        display: inline-block;
        padding: 8px 15px;
        margin-top: 20px;
        background: #004080;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    a.button:hover {
        background: #0066cc;
    }
    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn-delete:hover {
        background: #c82333;
    }
    .message {
        background: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #c3e6cb;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Registered Students</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (count($students) == 0): ?>
        <p>No students found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Registration Number</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['full_name']) ?></td>
                    <td><?= htmlspecialchars($student['reg_no']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this student?');" style="margin:0;">
                            <input type="hidden" name="delete_student_id" value="<?= $student['id'] ?>">
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="button">Back to Admin Dashboard</a>
</div>

</body>
</html>

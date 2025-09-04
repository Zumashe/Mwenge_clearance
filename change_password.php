<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header('Location: login.php');
    exit;
}

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    die("No student specified.");
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass = trim($_POST['new_password']);
    if ($new_pass === '') {
        $message = "Password cannot be empty.";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :pass WHERE id = :id");
        $stmt->execute([':pass' => $hashed, ':id' => $user_id]);
        $message = "Password updated successfully!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Change Student Password</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
.form-container { max-width: 400px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
input[type=password], button { width: 100%; padding: 10px; margin-top: 10px; }
button { background: #004080; color: white; border: none; cursor: pointer; }
button:hover { background: #0066cc; }
.msg { margin-top: 10px; padding: 10px; background: #dff0d8; }
</style>
</head>
<body>

<div class="form-container">
    <h2>Change Password</h2>
    <?php if ($message): ?>
        <div class="msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>New Password:</label>
        <input type="password" name="new_password" required>
        <button type="submit">Update Password</button>
    </form>
    <p><a href="admin_dashboard.php">⬅ Back to Dashboard</a></p>
</div>

</body>
</html>

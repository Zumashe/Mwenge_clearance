<?php
session_start();
require_once 'db.php';  // connection

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header("Location: login.php");
    exit;
}

$message = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $reg_no = trim($_POST['reg_no']);
    $full_name = trim($_POST['full_name']);
    $password = trim($_POST['password']);

    if (!$reg_no || !$full_name) {
        $message = "Please fill all required fields.";
    } else {
        // Get user by reg_no
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$reg_no]);
        $user = $stmt->fetch();

        if (!$user) {
            $message = "User with Reg No not found.";
        } else {
            $user_id = $user['id'];

            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET full_name = ?, password = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$full_name, $hashedPassword, $user_id]);
            } else {
                $sql = "UPDATE users SET full_name = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$full_name, $user_id]);
            }

            $message = "User updated successfully.";
        }
    }
}

// Fetch all users to show in table
$stmt = $pdo->query("SELECT id, full_name, username FROM users ORDER BY full_name ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Users</title>
<style>
    body { font-family: Arial, sans-serif; background: #f7f9fc; padding: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #004080; color: white; }
    form { margin-top: 20px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
    button { margin-top: 15px; padding: 10px 20px; background: #004080; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #0066cc; }
    .message { margin-top: 15px; padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; }
    .back-btn { display: inline-block; background: #008000; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-bottom: 20px; }
    .back-btn:hover { background: #005900; }
</style>
</head>
<body>

<a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>

<h2>Manage Users (Edit by Reg No)</h2>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post">
    <label for="reg_no">Registration Number (username)</label>
    <input type="text" id="reg_no" name="reg_no" placeholder="Enter user's Reg No" required>

    <label for="full_name">Full Name</label>
    <input type="text" id="full_name" name="full_name" placeholder="Enter full name" required>

    <label for="password">New Password (leave blank if no change)</label>
    <input type="password" id="password" name="password">

    <button type="submit" name="update_user">Update User</button>
</form>

<h3>All Registered Users</h3>
<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Registration Number</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

<?php
session_start();
require_once 'db.php'; // inayo $pdo

// Hakikisha user ame-login na ni HOD (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit;
}

$full_name = $_SESSION['full_name'] ?? 'HOD';

// Handle status update POST
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    if ($request_id && in_array($new_status, ['pending', 'approved', 'rejected'])) {
        $stmt = $pdo->prepare("UPDATE clearance_requests SET status_hod = ? WHERE id = ?");
        $stmt->execute([$new_status, $request_id]);
        $message = "Status updated successfully.";
    } else {
        $message = "Invalid status update.";
    }
}

// Chukua requests zote, unaweza kubadilisha query kuonyesha zile za HOD tu au zote
$stmt = $pdo->query("SELECT * FROM clearance_requests ORDER BY request_date DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>HOD Dashboard - Mwenge Clearance System</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        padding: 30px;
        max-width: 950px;
        margin: auto;
    }
    h2 {
        color: #004080;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background: white;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #004080;
        color: white;
    }
    .message {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 5px;
        text-align: center;
    }
    .logout-btn {
        float: right;
        background: #e74c3c;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
    }
    .logout-btn:hover {
        background: #c0392b;
    }
    select {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #bbb;
    }
    button.update-btn {
        background-color: #004080;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }
    button.update-btn:hover {
        background-color: #0066cc;
    }
</style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($full_name); ?> - HOD Dashboard</h2>
<a href="logout.php" class="logout-btn">Logout</a>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Request Date</th>
            <th>Full Name</th>
            <th>Reg No</th>
            <th>Programme</th>
            <th>Academic Year</th>
            <th>HOD Status</th>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $request): ?>
        <tr>
            <td><?php echo htmlspecialchars($request['request_date']); ?></td>
            <td><?php echo htmlspecialchars($request['full_name']); ?></td>
            <td><?php echo htmlspecialchars($request['reg_no']); ?></td>
            <td><?php echo htmlspecialchars($request['programme']); ?></td>
            <td><?php echo htmlspecialchars($request['academic_year']); ?></td>
            <td><?php echo ucfirst($request['status_hod'] ?? 'pending'); ?></td>
            <td>
                <form method="post" style="margin:0;">
                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                    <select name="new_status" required>
                        <option value="pending" <?= ($request['status_hod'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($request['status_hod'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($request['status_hod'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                    <button type="submit" name="update_status" class="update-btn">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

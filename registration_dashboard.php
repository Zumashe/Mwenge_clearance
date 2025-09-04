<?php
session_start();
require_once 'db.php'; // PDO connection (inayo $pdo)

// Hakikisha user ame-login na ana role_id = 7 (Registration Officer)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 7) {
    header('Location: login.php');
    exit;
}

$full_name = $_SESSION['full_name'] ?? 'Registration Officer';

// Handle status update POST
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['new_status'])) {
    $request_id = intval($_POST['request_id']);
    $new_status = $_POST['new_status'];
    
    // Validate new_status
    $allowed_status = ['pending', 'approved', 'rejected'];
    if (in_array($new_status, $allowed_status)) {
        // Update the status_registration field for the request
        $stmt = $pdo->prepare("UPDATE clearance_requests SET status_registration = ? WHERE id = ?");
        if ($stmt->execute([$new_status, $request_id])) {
            $message = "Status updated successfully.";
        } else {
            $message = "Failed to update status.";
        }
    } else {
        $message = "Invalid status selected.";
    }
}

// Fetch all clearance requests, if status_registration is NULL or empty, treat as pending
$stmt = $pdo->query("SELECT *, 
    COALESCE(NULLIF(status_registration, ''), 'pending') AS effective_status 
    FROM clearance_requests ORDER BY request_date DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Registration Officer Dashboard</title>
<style>
    body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; max-width: 1000px; margin: auto; }
    h2 { color: #004080; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background: #004080; color: white; }
    button { background: #004080; color: white; border: none; padding: 5px 12px; cursor: pointer; border-radius: 4px; }
    button:hover { background: #0066cc; }
    select { padding: 5px; border-radius: 4px; }
    .logout-btn { float: right; background: #c0392b; color: white; border: none; padding: 8px 14px; border-radius: 4px; cursor: pointer; }
    .logout-btn:hover { background: #e74c3c; }
    .message { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; margin-bottom: 15px; border-radius: 5px; }
</style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($full_name) ?></h2>
<form action="logout.php" method="post" style="float:right; margin-bottom: 20px;">
    <button type="submit" class="logout-btn">Logout</button>
</form>

<h3>Registration Office - Clearance Requests</h3>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Reg No</th>
            <th>Programme</th>
            <th>Academic Year</th>
            <th>Current Status</th>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($requests)): ?>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['full_name']) ?></td>
                    <td><?= htmlspecialchars($req['reg_no']) ?></td>
                    <td><?= htmlspecialchars($req['programme']) ?></td>
                    <td><?= htmlspecialchars($req['academic_year']) ?></td>
                    <td><?= ucfirst(htmlspecialchars($req['effective_status'])) ?></td>
                    <td>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                            <select name="new_status" required>
                                <option value="pending" <?= $req['effective_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $req['effective_status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $req['effective_status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No clearance requests found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

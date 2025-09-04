<?php
session_start();
require_once 'db.php';

// Hakikisha mtumiaji ame-login na ni library (role_id = 9)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 9) {
    header('Location: login.php');
    exit;
}

$full_name = $_SESSION['full_name'] ?? 'Library';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    if ($request_id && in_array($new_status, ['pending', 'approved', 'rejected'])) {
        $stmt = $pdo->prepare("UPDATE clearance_requests SET status_library = ? WHERE id = ?");
        $stmt->execute([$new_status, $request_id]);
        $message = "Status updated successfully.";
    } else {
        $message = "Invalid status update.";
    }
}

// Fetch all requests
$stmt = $pdo->query("SELECT * FROM clearance_requests ORDER BY request_date DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9fbfd;
            margin: 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 { color: #004080; margin-bottom: 10px; }
        h3 { color: #003366; margin-bottom: 20px; }
        table {
            width: 100%;
            max-width: 900px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        thead { background: #004080; color: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        tr:hover { background: #eef5ff; }
        select, button {
            padding: 6px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #004080;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background: #0066cc; }
        .logout-btn {
            margin-top: 20px;
            background: #e74c3c;
            padding: 8px 15px;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover { background: #c0392b; }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($full_name) ?></h2>
<h3>Library Dashboard</h3>

<?php if (!empty($message)): ?>
    <div style="color:green;font-weight:bold;"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Reg No</th>
            <th>Programme</th>
            <th>Academic Year</th>
            <th>Status</th>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($requests): ?>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['full_name']) ?></td>
                    <td><?= htmlspecialchars($req['reg_no']) ?></td>
                    <td><?= htmlspecialchars($req['programme']) ?></td>
                    <td><?= htmlspecialchars($req['academic_year']) ?></td>
                    <td><?= ucfirst($req['status_library'] ?: 'pending') ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                            <select name="new_status">
                                <option value="pending" <?= ($req['status_library'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= ($req['status_library'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= ($req['status_library'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No clearance requests found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<form action="logout.php" method="post">
    <button type="submit" class="logout-btn">Logout</button>
</form>

</body>
</html>

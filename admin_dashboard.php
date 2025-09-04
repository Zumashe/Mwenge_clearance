<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header('Location: login.php');
    exit;
}

$full_name = $_SESSION['full_name'] ?? 'Admin';

// Fetch clearance requests
$sql = "SELECT c.*, u.full_name AS student_name, u.email, u.id AS student_id, u.username AS reg_no
        FROM clearance_requests c
        JOIN users u ON c.user_id = u.id
        ORDER BY c.request_date DESC";
$stmt = $pdo->query($sql);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Admin Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background: #eef2f5; margin: 0; padding: 20px; }
h2 { color: #003366; }
.container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background: #004080; color: #fff; }
.btn { background: #004080; color: #fff; padding: 6px 10px; border-radius: 4px; text-decoration: none; }
.btn:hover { background: #0066cc; }
.btn-delete { background: #cc0000; }
.btn-delete:hover { background: #ff0000; }
.links { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
.links a { flex: 1; text-align: center; background: #0073e6; color: #fff; padding: 10px; border-radius: 6px; text-decoration: none; }
.links a:hover { background: #005bb5; }
.logout-btn { float: right; background: #cc0000; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
.logout-btn:hover { background: #ff0000; }
</style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($full_name) ?></h2>
    <form action="logout.php" method="post" style="display:inline;">
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <h3>All Clearance Requests</h3>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Reg No</th>
                <th>Programme</th>
                <th>Academic Year</th>
                <th>Registration</th>
                <th>Finance</th>
                <th>Dean</th>
                <th>MWECASO</th>
                <th>HOD</th>
                <th>Hostel</th>
                <th>Faculty</th>
                <th>Library</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($requests)): ?>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($req['student_name']) ?><br>
                        <a href="change_password.php?user_id=<?= $req['student_id'] ?>" class="btn">Change Password</a>
                    </td>
                    <td><?= htmlspecialchars($req['reg_no']) ?></td>
                    <td><?= htmlspecialchars($req['programme']) ?></td>
                    <td><?= htmlspecialchars($req['academic_year']) ?></td>
                    <td><?= ucfirst($req['status_registration']) ?></td>
                    <td><?= ucfirst($req['status_finance']) ?></td>
                    <td><?= ucfirst($req['status_dean_of_student']) ?></td>
                    <td><?= ucfirst($req['status_mwecauso']) ?></td>
                    <td><?= ucfirst($req['status_hod']) ?></td>
                    <td><?= ucfirst($req['status_hostel']) ?></td>
                    <td><?= ucfirst($req['status_head_of_faculty']) ?></td>
                    <td><?= ucfirst($req['status_library']) ?></td>
                    <td><?= htmlspecialchars($req['request_date']) ?></td>
                    <td>
                        <form method="post" action="delete_request.php" onsubmit="return confirm('Are you sure to delete this request?');">
                            <input type="hidden" name="id" value="<?= $req['id'] ?>">
                            <button type="submit" class="btn btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="14">No clearance requests found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="links">
        <a href="registered_students.php">View Registered Students</a>
        
        
        <a href="approved_students.php">View Approved Students</a>
    </div>
</div>
</body>
</html>

<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT c.id, c.full_name, c.reg_no, c.programme, c.academic_year
        FROM clearance_requests c
        WHERE c.status_registration='approved'
        AND c.status_finance='approved'
        AND c.status_dean_of_student='approved'
        AND c.status_mwecauso='approved'
        AND c.status_hod='approved'
        AND c.status_hostel='approved'
        AND c.status_head_of_faculty='approved'
        AND c.status_library='approved'
        ORDER BY c.full_name ASC";

$stmt = $pdo->query($sql);
$approved_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Approved Students</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; }
        h2 { color: #004080; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #004080; color: white; }
        .btn { display:inline-block; padding:8px 15px; background:#004080; color:white; text-decoration:none; border-radius:5px; }
        .btn:hover { background:#0066cc; }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="btn">← Back to Dashboard</a>
<h2>Approved Students List</h2>

<?php if (count($approved_students) == 0): ?>
    <p>No students have been fully approved yet.</p>
<?php else: ?>
    <a href="download_approved_pdf.php" class="btn" target="_blank"> Download PDF</a>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Reg No</th>
                <th>Programme</th>
                <th>Academic Year</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($approved_students as $st): ?>
            <tr>
                <td><?= htmlspecialchars($st['full_name']) ?></td>
                <td><?= htmlspecialchars($st['reg_no']) ?></td>
                <td><?= htmlspecialchars($st['programme']) ?></td>
                <td><?= htmlspecialchars($st['academic_year']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>

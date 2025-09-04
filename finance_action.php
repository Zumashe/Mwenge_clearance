<?php
session_start();
include 'db.php';

// Check if user is finance
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'finance') {
    header('Location: login.php');
    exit;
}

// Get all clearance requests with status pending or approved
$sql = "SELECT id, reg_no, full_name, academic_year, programme, status_finance 
        FROM clearance_requests 
        WHERE status_finance IN ('pending', 'approved')
        ORDER BY request_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Finance Dashboard - Mwecau Clearance System</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #004080;
            color: white;
        }
        button {
            margin-right: 8px;
            padding: 6px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>Finance Clearance Requests</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Reg No</th>
                    <th>Full Name</th>
                    <th>Academic Year</th>
                    <th>Programme</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['programme']); ?></td>
                    <td><?php echo ucfirst($row['status_finance']); ?></td>
                    <td>
                        <?php if ($row['status_finance'] === 'pending'): ?>
                            <form method="post" action="finance_action.php" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        <?php else: ?>
                            <strong><?php echo ucfirst($row['status_finance']); ?></strong>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No clearance requests found.</p>
    <?php endif; ?>
</body>
</html>

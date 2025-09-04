<?php
session_start();
include 'db.php';

// Check if user is Registration staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'registration') {
    header('Location: login.php');
    exit;
}

// Handle Approve/Reject POST action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    if (in_array($action, ['approve', 'reject'])) {
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $stmt = $conn->prepare("UPDATE clearance_requests SET status_registration = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $request_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to avoid form resubmission
        header("Location: registration_dashboard.php");
        exit;
    }
}

// Fetch clearance requests relevant to Registration office with status pending or approved
$sql = "SELECT id, reg_no, full_name, academic_year, programme, status_registration 
        FROM clearance_requests 
        WHERE status_registration IN ('pending', 'approved')
        ORDER BY request_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registration Dashboard - Mwecau Clearance System</title>
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
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Registration Clearance Requests</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Registration Number</th>
                    <th>Full Name</th>
                    <th>Academic Year</th>
                    <th>Programme</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['programme']); ?></td>
                    <td><?php echo ucfirst($row['status_registration']); ?></td>
                    <td>
                        <?php if ($row['status_registration'] === 'pending'): ?>
                            <form method="post" action="registration_dashboard.php" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        <?php else: ?>
                            <strong><?php echo ucfirst($row['status_registration']); ?></strong>
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

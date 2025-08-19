<?php
session_start();
require_once 'db.php'; // Inaunganisha kwa kutumia $pdo

// Hakikisha user ameingia na role_id yake ni 10 (Admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 10) {
    header('Location: login.php');
    exit;
}

$admin_name = $_SESSION['full_name'] ?? 'Admin';

// Chukua clearance requests pamoja na taarifa za wanafunzi
$sql = "SELECT c.id, u.full_name, u.username, u.program, u.academic_year, c.status, c.certificate
        FROM clearance_requests c
        JOIN users u ON c.student_id = u.id
        ORDER BY c.id DESC";

$stmt = $pdo->query($sql);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Mwenge Clearance System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Welcome, <?= htmlspecialchars($admin_name) ?></h2>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>

  <h4>Student Clearance Requests</h4>
  <table class="table table-bordered table-hover mt-3">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Full Name</th>
        <th>Username</th>
        <th>Program</th>
        <th>Academic Year</th>
        <th>Status</th>
        <th>Certificate</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php if (!empty($requests)): ?>
      <?php foreach($requests as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['program']) ?></td>
          <td><?= htmlspecialchars($row['academic_year']) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td>
            <?php if (!empty($row['certificate'])): ?>
              <a href="certificates/<?= htmlspecialchars($row['certificate']) ?>" target="_blank">View</a>
            <?php else: ?>
              Not Uploaded
            <?php endif; ?>
          </td>
          <td>
            <form action="delete_request.php" method="POST" style="display:inline;">
              <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this request?')">Delete</button>
            </form>
            <form action="upload_certificate.php" method="POST" enctype="multipart/form-data" style="display:inline;">
              <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
              <input type="file" name="certificate" accept=".pdf,.jpg,.png" required style="width: 140px;">
              <button type="submit" class="btn btn-sm btn-success mt-1">Upload</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="8" class="text-center">No requests found.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>

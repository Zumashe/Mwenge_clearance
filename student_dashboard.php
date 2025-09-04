<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$reg_no = $_SESSION['reg_no'];

// Angalia kama tayari mwanafunzi ana request yoyote
$stmt = $pdo->prepare("SELECT * FROM clearance_requests WHERE user_id = :user_id ORDER BY request_date DESC");
$stmt->execute([':user_id' => $user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
$has_request = count($requests) > 0;

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_clearance'])) {
    if ($has_request) {
        $message = "You already submitted a clearance request. You cannot submit again.";
    } else {
        $input_full_name = trim($_POST['full_name']);
        $input_reg_no = trim($_POST['reg_no']);
        $programme = trim($_POST['programme']);
        $academic_year = trim($_POST['academic_year']);

        if (!$input_full_name || !$input_reg_no || !$programme || !$academic_year) {
            $message = "Please fill all fields.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO clearance_requests 
                (user_id, reg_no, full_name, academic_year, programme,
                 status_finance, status_dean_of_student, status_mwecauso, status_hod,
                 status_hostel, status_registration, status_head_of_faculty, status_library, request_date) 
                VALUES (:user_id, :reg_no, :full_name, :academic_year, :programme,
                 'pending', 'pending', 'pending', 'pending', 'pending', 'pending', 'pending', 'pending', NOW())");
            
            $stmt->execute([
                ':user_id' => $user_id,
                ':reg_no' => $input_reg_no,
                ':full_name' => $input_full_name,
                ':academic_year' => $academic_year,
                ':programme' => $programme
            ]);

            $message = "Clearance request submitted successfully.";
            $has_request = true; // sasa mwanafunzi ana request
        }
    }
}

// Angalia kama request ya mwisho ime-approved zote
$all_approved = false;
$latest_request = null;
if ($requests) {
    $latest_request = $requests[0];
    $statuses = [
        $latest_request['status_finance'],
        $latest_request['status_dean_of_student'],
        $latest_request['status_mwecauso'],
        $latest_request['status_hod'],
        $latest_request['status_hostel'],
        $latest_request['status_registration'],
        $latest_request['status_head_of_faculty'],
        $latest_request['status_library']
    ];
    $all_approved = !in_array('pending', $statuses) && !in_array('rejected', $statuses);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Student Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 40px; }
.dashboard { max-width: 750px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 12px rgba(0,0,0,0.1); }
h2 { color: #004080; }
label { display: block; margin-top: 12px; font-weight: bold; }
input[type="text"] { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
button { margin-top: 20px; background: #004080; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; cursor: pointer; font-size: 16px; }
button:hover { background: #0066cc; }
table { width: 100%; border-collapse: collapse; margin-top: 30px; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
th { background: #004080; color: white; }
.message { background: #dff0d8; color: #3c763d; padding: 10px; margin-top: 20px; border-radius: 5px; }
.download-btn {
    display:inline-block;
    background: green;
    color: white;
    padding: 10px 15px;
    margin-top: 15px;
    text-decoration: none;
    border-radius: 5px;
}
.download-btn:hover { background: darkgreen; }
</style>
</head>
<body>

<div class="dashboard">
<h2>Welcome, <?php echo htmlspecialchars($full_name); ?></h2>
<p><strong>Registration Number:</strong> <?php echo htmlspecialchars($reg_no); ?></p>

<?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (!$has_request): ?>
<form method="post">
    <label for="full_name">Full Name</label>
    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>

    <label for="reg_no">Registration Number</label>
    <input type="text" id="reg_no" name="reg_no" value="<?php echo htmlspecialchars($reg_no); ?>" required>

    <label for="programme">Programme</label>
    <input type="text" id="programme" name="programme" placeholder="Enter your programme" required>

    <label for="academic_year">Academic Year</label>
    <input type="text" id="academic_year" name="academic_year" placeholder="e.g. 2024/2025" required>

    <button type="submit" name="submit_clearance">Submit Clearance Request</button>
</form>
<?php else: ?>
    <p style="color:red; font-weight:bold;">You have already submitted a clearance request. You cannot submit another one.</p>
<?php endif; ?>

<h3>Your Clearance Requests</h3>
<?php if (!$requests): ?>
    <p>You have no clearance requests submitted.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Request Date</th>
                <th>Programme</th>
                <th>Academic Year</th>
                <th>Finance</th>
                <th>Dean of Student</th>
                <th>Mwecauso</th>
                <th>HOD</th>
                <th>Hostel</th>
                <th>Registration</th>
                <th>Head of Faculty</th>
                <th>Library</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($requests as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['programme']); ?></td>
                    <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                    <td><?php echo ucfirst($row['status_finance']); ?></td>
                    <td><?php echo ucfirst($row['status_dean_of_student']); ?></td>
                    <td><?php echo ucfirst($row['status_mwecauso']); ?></td>
                    <td><?php echo ucfirst($row['status_hod']); ?></td>
                    <td><?php echo ucfirst($row['status_hostel']); ?></td>
                    <td><?php echo ucfirst($row['status_registration']); ?></td>
                    <td><?php echo ucfirst($row['status_head_of_faculty']); ?></td>
                    <td><?php echo ucfirst($row['status_library']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($all_approved && $latest_request): ?>
        <a href="download_certificate.php?request_id=<?= $latest_request['id']; ?>" class="download-btn">
            Download Clearance Certificate
        </a>
    <?php endif; ?>
<?php endif; ?>

<form action="logout.php" method="post" style="margin-top:20px;">
    <button type="submit" class="logout-btn">Logout</button>
</form>
</div>

</body>
</html>

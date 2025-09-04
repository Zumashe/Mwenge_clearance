<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($identifier === '' || $password === '') {
        $_SESSION['error'] = "All fields are required.";
        header("Location: login.php");
        exit;
    }

    // Check if identifier looks like student reg_no (e.g. starts with 't/' or 'T/')
    if (preg_match('/^t\//i', $identifier)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE reg_no = ? AND role_id = 1");
        $stmt->execute([$identifier]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$identifier]);
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Store session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['reg_no'] = $user['reg_no'] ?? '';
        $_SESSION['username'] = $user['username'] ?? '';

        // Redirect user based on role_id
        switch ($user['role_id']) {
            case 1:
                header("Location: student_dashboard.php");
                break;
            case 2:
                header("Location: dean_dashboard.php");
                break;
            case 3:
                header("Location: hod_dashboard.php");
                break;
            case 4:
                header("Location: mwecauso_dashboard.php");
                break;
            case 5:
                header("Location: library_dashboard.php");
                break;
            case 6:
                header("Location: hostel_dashboard.php");
                break;
                case 7:
                header("Location: registration_dashboard.php");
                break;
                case 8:
                header("Location: faculty_dashboard.php");
                break;
                case 9:
                header("Location: library_dashboard.php");
                break;
                case 10:
                header("Location: admin_dashboard.php");
                break;
            default:
                $_SESSION['error'] = "Unknown user role.";
                header("Location: login.php");
                exit;
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid registration number/username or password.";
        header("Location: login.php");
        exit;
    }
} else {
    // Block direct access
    header("Location: login.php");
    exit;
}

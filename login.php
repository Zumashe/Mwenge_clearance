<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // User already logged in, redirect based on role
    $role_id = $_SESSION['role_id'] ?? 0;

    switch ($role_id) {
        case 1:
            header("Location: student_dashboard.php");
            break;
        case 2:
            header("Location: finance_dashboard.php");
            break;
        case 3:
            header("Location: dean_dashboard.php");
            break;
        case 4:
            header("Location: mwecauso_dashboard.php");
            break;
        case 5:
            header("Location: hod_dashboard.php");
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
            $_SESSION['error'] = "Unknown role. Contact admin.";
            header("Location: login.php");
            exit;
    }
    exit; // Funga switch na pia isije kuendelea kuprint HTML
}

// Hapa kuendelea na login page

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - Clearance System</title>
<style>
  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: none; /* Hakuna background */
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  form {
    background: none; /* Hakuna background */
    border: 1px solid #ccc; /* Mstari mdogo wa pembeni */
    padding: 40px 50px;
    border-radius: 12px;
    width: 380px;
  }

  h2 {
    margin-bottom: 30px;
    color: #1e3c72;
    text-align: center;
    font-weight: 700;
  }

  label {
    display: block;
    margin: 18px 0 8px;
    font-weight: 600;
    color: #333;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 12px 16px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    outline: none;
  }

  input[type="text"]:focus,
  input[type="password"]:focus {
    border-color: #1e3c72;
  }

  button {
    margin-top: 30px;
    width: 100%;
    background-color: #1e3c72;
    color: white;
    font-size: 16px;
    font-weight: 700;
    padding: 12px 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
  }

  button:hover {
    background-color: #274e9e;
  }

  .error {
    color: #d8000c;
    border: 1px solid #f5c6cb;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
  }

  @media (max-width: 420px) {
    form {
      width: 90%;
      padding: 30px 20px;
    }
  }
</style>



</head>
<body>

<form method="post" action="process_login.php" novalidate>
  <h2>Login to mwecau Clearance System</h2>

  <?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <label for="identifier">Registration Number or Username</label>
  <input type="text" id="identifier" name="identifier" placeholder="e.g. t/12345 or johndoe" required autofocus>

  <label for="password">Password</label>
  <input type="password" id="password" name="password" placeholder="Enter your password" required>

  <button type="submit">Login</button>
  <div style="text-align:center; margin-top: 20px; color: #2c3e50; font-weight: 600;">
    <p>you have no acount? <a href="register.php" style="color: #4b6cb7; text-decoration: none; font-weight: 700;">register here</a></p>
  </div>
</form>

</body>
</html>

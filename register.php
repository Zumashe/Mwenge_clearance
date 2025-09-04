<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $reg_no = trim($_POST['reg_no']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($reg_no) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE reg_no = ? OR email = ?");
        $stmt->execute([$reg_no, $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Registration number or email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, reg_no, email, password, role_id, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
            $result = $stmt->execute([$full_name, $reg_no, $email, $hashed_password]);

            if ($result) {
                $success = "Registration successful. You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Student Registration - Mwecau Clearance System</title>
<style>
  body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: none; /* Hakuna background */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
  }

  form {
    background: none; /* Hakuna background */
    border: 1px solid #ccc; /* Mstari wa pembeni */
    padding: 30px 40px;
    border-radius: 10px;
    width: 400px;
    max-width: 100%;
  }

  h2 {
    text-align: center;
    color: #1e3c72;
    margin-bottom: 20px;
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #333;
  }

  input[type="text"],
  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
  }

  input:focus {
    border-color: #1e3c72;
    outline: none;
  }

  button {
    width: 100%;
    background-color: #1e3c72;
    color: white;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }

  button:hover {
    background-color: #274e9e;
  }

  .message {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: bold;
  }

  .error {
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  .success {
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  p {
    text-align: center;
    margin-top: 10px;
  }

  a {
    color: #1e3c72;
    font-weight: bold;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>

<form method="post" action="" id="registerForm">
  <h2>Student Registration</h2>

  <?php if (!empty($error)): ?>
    <div class="message error"><?=htmlspecialchars($error)?></div>
  <?php elseif (!empty($success)): ?>
    <div class="message success"><?= $success ?></div>
  <?php endif; ?>

  <label for="full_name">Full Name:</label>
  <input type="text" id="full_name" name="full_name" required value="<?=htmlspecialchars($_POST['full_name'] ?? '')?>">

  <label for="reg_no">Registration Number:</label>
  <input type="text" id="reg_no" name="reg_no" required value="<?=htmlspecialchars($_POST['reg_no'] ?? '')?>">

  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">

  <label for="password">Password:</label>
  <input type="password" id="password" name="password" required minlength="6">

  <label for="confirm_password">Confirm Password:</label>
  <input type="password" id="confirm_password" name="confirm_password" required minlength="6">

  <button type="submit">Register</button>

  <p>
    Already registered? <a href="login.php">Login here</a>
  </p>
</form>

<script>
document.getElementById("registerForm").addEventListener("submit", function(e){
    let pass = document.getElementById("password").value;
    let confirmPass = document.getElementById("confirm_password").value;

    if(pass.length < 6){
        alert("Password must be at least 6 characters.");
        e.preventDefault();
    } else if(pass !== confirmPass){
        alert("Passwords do not match.");
        e.preventDefault();
    }
});
</script>

</body>
</html>

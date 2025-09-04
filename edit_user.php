<?php
// edit_user.php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($id) || empty($username) || empty($password)) {
        echo "Please fill all fields.";
        exit;
    }

    // Update the user in the database
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssi", $username, $password, $id);

    if ($stmt->execute()) {
        echo "User updated successfully.";
    } else {
        echo "Failed to update user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

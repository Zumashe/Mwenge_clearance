<?php
include 'db.php'; // hakikisha $conn ipo

// Hatua ya 1: Ikiwa ID haijatumwa bado
if (!isset($_POST['id']) && !isset($_GET['id'])) {
    // Onyesha fomu ya kuingiza ID
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Enter User ID</title>
    </head>
    <body>
        <h2>Enter User ID to Edit</h2>
        <form method="post" action="edit_user.php">
            <label>Enter User ID:</label>
            <input type="number" name="id" required>
            <button type="submit">Fetch User</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Hatua ya 2: Chukua ID kutoka POST au GET
$id = isset($_POST['id']) ? intval($_POST['id']) : intval($_GET['id']);

// Tafuta mtumiaji
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found for ID: " . htmlspecialchars($id);
    exit;
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User Info</h2>

    <form method="POST" action="update_user.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>

        <label>New Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Update User</button>
    </form>
</body>
</html>

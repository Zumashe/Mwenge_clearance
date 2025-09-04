<?php
require_once 'db.php';  // Unganisha database connection yako hapa

// Angalia role_id ya admin, mfano role_id = 10
$adminUsername = 'admin'; // username ya admin
$newPassword = 'Admin1234!'; // password mpya unayotaka kuweka

// Hash password mpya
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    // Update password kwa admin
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ? AND role_id = 10");
    $stmt->execute([$hashedPassword, $adminUsername]);

    if ($stmt->rowCount() > 0) {
        echo "Password ya admin imebadilishwa kuwa: " . htmlspecialchars($newPassword) . "<br>";
        echo "Sasa unaweza kujaribu ku-login kwa password hii mpya.";
    } else {
        echo "Hakuna admin aliyepatikana au password haikubadilishwa.";
    }
} catch (PDOException $e) {
    echo "Kosa: " . $e->getMessage();
}
?>

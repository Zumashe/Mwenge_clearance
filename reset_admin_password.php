<?php
require_once 'db.php'; // ensure it connects using PDO

// Username ya admin
$username = 'admin';

// Password mpya (weka yako hapa)
$newPassword = 'admin1234'; // weka password unayotaka

// Funga password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Andaa query ya kubadilisha password
$sql = "UPDATE users SET password = ? WHERE username = ?";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([$hashedPassword, $username]);

if ($result) {
    echo "Password ya admin imerekebishwa kuwa '$newPassword'. Tafadhali login upya na password hiyo.";
} else {
    echo "Imeshindikana kubadilisha password.";
}
?>

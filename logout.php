<?php
session_start();

// Futa session zote na kuondoa cookies za session
$_SESSION = [];
session_unset();
session_destroy();

// Rudisha kwenye ukurasa wa login
header("Location: login.php");
exit;

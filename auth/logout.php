<?php
// Session start (agar already start nahi hui)
session_start();

// Sab session data remove kar do
session_unset();

// Session completely destroy kar do
session_destroy();

// Browser cache disable (taake back button se login page open na ho)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Login page par redirect
header("Location: login.php");
exit;
?>

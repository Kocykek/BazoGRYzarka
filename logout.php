<?php
session_start();
// Clear all session data
$_SESSION = [];
session_destroy();

// Redirect to login or homepage after logout
header("Location: main");
exit;
?>
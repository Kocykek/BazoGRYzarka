<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

echo "Witaj, " . htmlspecialchars($_SESSION['user']) . "!";
?>

<a href="logout.php">Wyloguj się</a>

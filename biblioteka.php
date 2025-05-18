<?php
session_start();

if (!isset($_SESSION['user'])) {
    // User is not logged in — redirect to login page
    header("Location: login.php");
    exit();
}
else
{
    echo "xd";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Twoja Biblioteka</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Witaj, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
    <p>To jest twoja biblioteka gier.</p>
    <!-- Here you can list the user's games, etc. -->
</body>
</html>

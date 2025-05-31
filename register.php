<?php
session_start();
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['repeat-password'];

    // Basic validation
    if (!$username || !$email || !$password) {
        $errors[] = "Wszystkie pola są wymagane.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Hasła nie są takie same.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Niepoprawny email.";
    }

    if (empty($errors)) {
        // Check if username or email exists
        $stmt = $conn->prepare("SELECT Id_Uzytkownika FROM Uzytkownik WHERE Nick = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Użytkownik lub email już istnieje.";
        } else {
            // Insert new user with password hash
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Uzytkownik (nick, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password_hash);
            if ($stmt->execute()) {
                $_SESSION['user'] = $username;
                header("Location: dashboard.php"); // or main page
                exit;
            } else {
                $errors[] = "Błąd rejestracji. Spróbuj ponownie.";
            }
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazoGRYzarka - Rejestracja</title>
    <link rel="stylesheet" href="rejestracja.css">
</head>
<body>
<video autoplay muted loop id="bg-video">
        <source src="background.mp4" type="video/mp4">
    </video>
    
    
    <div class="overlay"></div>
    <div class="register-container">
        
        <div class="logo-container">
            <img src="logo2.png" alt="BazoGRYzarka Logo" class="logo">
        </div>
        
		<h1>BazoGRYzarka</h2>
        <h2>Rejestracja</h2>
        
        
        <form class="register-form" method="POST" action="">
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" id="login" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Adres e-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Hasło</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="repeat-password">Powtórz Hasło</label>
                <input type="password" id="repeat-password" name="repeat-password" required>
            </div>
            
            <button type="submit" class="register-btn">ZAREJESTRUJ</button>
        </form>
        
        
        <div class="register-link">
            Masz już konto?<br>
            <a href="login">KLIKNIJ TUTAJ aby się zalogować</a>
        </div>
    </div>
</body>
</html>
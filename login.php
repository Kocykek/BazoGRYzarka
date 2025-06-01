<?php
session_start();

$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT Id_Uzytkownika, password_hash, ZdjecieProfilowe FROM Uzytkownik WHERE Nick = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $password_hash, $zdjecieProfilowe);
        if ($stmt->fetch()) {
            if (password_verify($password, $password_hash)) {
                $_SESSION['user'] = $username;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['zdjecieProfilowe'] = $zdjecieProfilowe;
                header("Location: profile"); // or main page
                exit;
            } else {
                $error = "Niepoprawne hasło.";
            }
        } else {
            $error = "Nie znaleziono użytkownika.";
        }
        $stmt->close();
    } else {
        $error = "Proszę podać login i hasło.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazoGRYzarka - Logowanie</title>
    <link rel="stylesheet" href="logowanie.css">
</head>
<body>
    
    <video autoplay muted loop style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -2; opacity: 0.15;">>
        <source src="images/background.mp4" type="video/mp4">
    </video>
    <div class="video-overlay"></div>
    
    
    <div class="login-container">
      
        <div class="logo-container">
            <img src="logo2.png" alt="BazoGRYzarka Logo" class="logo">
        </div>
		
        <h1>BazoGRYzarka</h1>
        <h2>Logowanie</h2>
        
        
        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" id="login" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Hasło</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            
            
            <button type="submit" class="login-btn">ZALOGUJ</button>
        </form>
        
        
        <div class="register-link">
            Nie masz jeszcze konta?<br>
            <a href="register">KLIKNIJ TUTAJ aby się zarejestrować</a>
        </div>
    </div>
</body>
</html>
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
        $stmt = $conn->prepare("SELECT password_hash FROM Uzytkownik WHERE Nick = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($password_hash);
        if ($stmt->fetch()) {
            if (password_verify($password, $password_hash)) {
                $_SESSION['user'] = $username;
                header("Location: dashboard.php"); // or main page
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
    <title>Logowanie</title>
</head>
<body>
<h2>Logowanie</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Login: <input type="text" name="username" required></label><br>
    <label>Hasło: <input type="password" name="password" required></label><br>
    <button type="submit">Zaloguj się</button>
</form>

<p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
</body>
</html>

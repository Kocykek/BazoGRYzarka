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
    $password_confirm = $_POST['password_confirm'];

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
    <title>Rejestracja</title>
</head>
<body>
<h2>Rejestracja</h2>

<?php if ($errors): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="">
    <label>Login: <input type="text" name="username" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Hasło: <input type="password" name="password" required></label><br>
    <label>Potwierdź hasło: <input type="password" name="password_confirm" required></label><br>
    <button type="submit">Zarejestruj się</button>
</form>

<p>Masz konto? <a href="login.php">Zaloguj się</a></p>
</body>
</html>

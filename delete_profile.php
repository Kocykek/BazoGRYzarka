<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $user_id = (int)$_SESSION['user_id'];

    $conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 1. Usuń recenzje użytkownika
    $stmt = $conn->prepare("DELETE FROM Recenzje WHERE Id_Uzytkownika = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 2. Usuń gry użytkownika z tabeli UzytkownikGra
    $stmt = $conn->prepare("DELETE FROM UzytkownikGra WHERE Id_Uzytkownika = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 3. Usuń samego użytkownika
    $stmt = $conn->prepare("DELETE FROM Uzytkownik WHERE Id_Uzytkownika = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();
        session_destroy(); // Wyloguj użytkownika
        header("Location: main");
        exit();
    } else {
        echo "Nie udało się usunąć konta.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: profile");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Konto usunięte</title>
</head>
<body>
    <h1>Twoje konto zostało usunięte.</h1>
    <p>Dziękujemy za korzystanie z BazoGRYzarka.</p>
    <a href="register">Zarejestruj się ponownie</a> lub <a href="main">Powrót do strony głównej</a>
</body>
</html>

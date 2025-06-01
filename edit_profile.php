<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = (int)$_SESSION['user_id'];

// Obsługa POST (zapis zmian)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opis = $_POST['opis'] ?? '';
    $kraj = $_POST['kraj'] ?? '';
    // Obsługa uploadu zdjęcia
    $avatar = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['avatar']['tmp_name']);
        if (in_array($file_type, $allowed_types)) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $new_filename = 'avatar_' . $user_id . '.' . $ext;
            $destination = __DIR__ . '/images/' . $new_filename;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
                $avatar = $new_filename;
            } else {
                $error = "Błąd podczas przesyłania zdjęcia.";
            }
        } else {
            $error = "Nieprawidłowy format zdjęcia. Dozwolone: JPG, PNG, GIF.";
        }
    }

    // Aktualizacja bazy
    if (!isset($error)) {
        if ($avatar) {
            $stmt = $conn->prepare("UPDATE Uzytkownik SET Opis = ?, ZdjecieProfilowe = ?, Kraj = ? WHERE Id_Uzytkownika = ?");
            $stmt->bind_param("sssi", $opis, $avatar, $kraj, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE Uzytkownik SET Opis = ?, Kraj = ? WHERE Id_Uzytkownika = ?");
            $stmt->bind_param("ssi", $opis, $kraj, $user_id);
        }

        if ($stmt->execute()) {
            header("Location: profile");
            exit();
        } else {
            $error = "Błąd podczas aktualizacji profilu.";
        }
    }
}

// Pobierz aktualne dane użytkownika, by wypełnić formularz
$stmt = $conn->prepare("SELECT Opis, ZdjecieProfilowe, Kraj FROM Uzytkownik WHERE Id_Uzytkownika = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$conn->close();

$avatar = $user['ZdjecieProfilowe'] ?: 'default.jpg';
$opis = $user['Opis'] ?? '';
$kraj = $user['Kraj'] ?? '';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Edytuj profil</title>
    <link rel="stylesheet" href="profil.css" />
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <h1 class="platform-name"><a href="main">BazoGRYzarka</a></h1>
        <div class="nav-links">
            <a href="sklep">Sklep</a>
            <a href="library">Biblioteka</a>
            <a href="community">Społeczność</a>
            <a href="contact">Pomoc Techniczna</a>
        </div>
        <div class="user-profile">
            <img src="images/<?= htmlspecialchars($avatar) ?>" alt="User Avatar" class="user-avatar" width="40" height="40" />
            <span class="username"><a href="profile"><?= htmlspecialchars($_SESSION['user']) ?></a></span>
        </div>
    </div>
</nav>

<div class="edit-profile-container">
    <h1>Edytuj profil</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="avatar">Zdjęcie profilowe (JPG, PNG, GIF):</label><br/>
            <img src="images/<?= htmlspecialchars($avatar) ?>" alt="Avatar" width="100" height="100" /><br/>
            <input type="file" name="avatar" id="avatar" accept="image/*" />
        </div>
        <div>
            <label for="opis">Opis:</label><br/>
            <textarea name="opis" id="opis" rows="5" cols="40"><?= htmlspecialchars($opis) ?></textarea>
        </div>
        <div>
    <label for="kraj">Kraj:</label><br/>
    <input type="text" name="kraj" id="kraj" value="<?= htmlspecialchars($kraj) ?>" maxlength="100" />
</div>
        
        <div style="margin-top: 10px;">
            <button type="submit">Zapisz zmiany</button>
            <a href="profile" style="margin-left: 10px;">Anuluj</a>
        </div>
        
    </form>
</div>

</body>
</html>

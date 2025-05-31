<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT Nick, Imie, Nazwisko, Kraj, ZdjecieProfilowe, Opis, created_at FROM Uzytkownik WHERE Id_Uzytkownika = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Użytkownik nie znaleziony.";
    exit();
}

$user = $result->fetch_assoc();

$avatar = $user['ZdjecieProfilowe'] ?: 'default.jpg';

// Prepare statement joining UzytkownikGra and Gry by Id_Gry, filtering by user ID
$games_stmt = $conn->prepare("
    SELECT Gra.Tytul, Gra.zdjGlowne
    FROM UzytkownikGra
    INNER JOIN Gra ON UzytkownikGra.Id_Gry = Gra.IdGry
    WHERE UzytkownikGra.Id_Uzytkownika = ?
");
$games_stmt->bind_param("i", $user_id);
$games_stmt->execute();
$games_result = $games_stmt->get_result();

$ownedGames = [];
while ($row = $games_result->fetch_assoc()) {
    $ownedGames[] = $row;
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BazoGRYzarka - Profil</title>
    <link rel="stylesheet" href="profil.css" />
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <h1 class="platform-name"><a href="main">BazoGRYzarka</a></h1>
        <div class="nav-links">
            <a href="sklep">Sklep</a>
            <a id="selectedLinkFromNav" href="library">Biblioteka</a>
            <a href="community">Społeczność</a>
            <a href="contact">Pomoc Techniczna</a>
        </div>
        <div class="user-profile">
            <img src="images/<?php echo htmlspecialchars($avatar); ?>" alt="User Avatar" class="user-avatar" width="40" height="40" />
            <span class="username"><a href="profile.php"><?php echo htmlspecialchars($user['Nick']); ?></a></span>
        </div>
    </div>
</nav>

<div class="profile-container">
    <div class="profile-section">
        <div class="profile-bubble">
            <div class="profile-header">
                <img src="images/<?php echo htmlspecialchars($avatar); ?>" alt="Profile Picture" class="profile-pic" width="100" height="100" />
                <h1><?php echo htmlspecialchars($user['Nick']); ?></h1>
            </div>
            <div class="profile-info">
                <p><strong><?php echo htmlspecialchars($user['Imie'] . ' ' . $user['Nazwisko']); ?></strong></p>
                <p><?php echo htmlspecialchars($user['Kraj'] ?? 'Nie podano kraju'); ?></p>
                <p>Użytkownik od: <?php echo htmlspecialchars(date('d-m-Y', strtotime($user['created_at']))); ?></p>
                <p class="bio"><?php echo htmlspecialchars($user['Opis'] ?? 'Brak opisu'); ?></p>
            </div>
        </div>

        <div class="action-buttons">
            <button class="edit-btn">EDYTUJ PROFIL</button>
            <button class="delete-btn">USUŃ PROFIL</button>
            <button class="share-btn">UDOSTĘPNIJ</button>
        </div>
    </div>

    <div class="games-section">
        <h2>Posiadane gry:</h2>
        <div class="games-grid">
    <?php if (count($ownedGames) > 0): ?>
        <?php foreach ($ownedGames as $game): ?>
            <div class="game-card">
                <?php
$imageFileName = $game['zdjGlowne'] ?? '';
$imagePath = "images/" . $imageFileName;

if (!file_exists($imagePath) || empty($imageFileName)) {
    $imagePath = "images/unknown.jpg";
}

$title = $game['Tytul'] ?? '';
?>

<img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($title); ?>" />

                <h3><?php echo htmlspecialchars($game['Tytul']); ?></h3>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Brak posiadanych gier.</p>
    <?php endif; ?>
</div>

    </div>
</div>
<div id="footer">
        <a href="privacy">Polityka prywatności</a>, 2025 BazoGRYzarka Prawa Zastrzeżone &copy;
    </div>
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Jeśli w URL jest user_id i jest to liczba, pokaż profil tego użytkownika, 
// w przeciwnym wypadku pokaż swój profil
$view_user_id = isset($_GET['user_id']) && ctype_digit($_GET['user_id']) ? (int)$_GET['user_id'] : (int)$_SESSION['user_id'];

// Pobierz dane użytkownika z bazy
$stmt = $conn->prepare("SELECT Nick, Imie, Nazwisko, Kraj, ZdjecieProfilowe, Opis, created_at FROM Uzytkownik WHERE Id_Uzytkownika = ?");
$stmt->bind_param("i", $view_user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Użytkownik nie znaleziony.";
    exit();
}

$user = $result->fetch_assoc();
$nick = $_SESSION['user'];

$stmt = $conn->prepare("SELECT ZdjecieProfilowe FROM Uzytkownik WHERE Nick = ?");
$stmt->bind_param("s", $nick);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $avatarTwoj = $row['ZdjecieProfilowe'] ?: 'default.jpg';
} else {
    $avatarTwoj = 'default.jpg';
}
// Pobierz gry użytkownika
$games_stmt = $conn->prepare("
    SELECT Gra.Tytul, Gra.zdjGlowne
    FROM UzytkownikGra
    INNER JOIN Gra ON UzytkownikGra.Id_Gry = Gra.IdGry
    WHERE UzytkownikGra.Id_Uzytkownika = ?
");
$games_stmt->bind_param("i", $view_user_id);
$games_stmt->execute();
$games_result = $games_stmt->get_result();

$ownedGames = [];
while ($row = $games_result->fetch_assoc()) {
    $ownedGames[] = $row;
}

$conn->close();

// Sprawdź, czy to jest profil zalogowanego użytkownika (do pokazania przycisków edycji/usunięcia)
$is_own_profile = $view_user_id === (int)$_SESSION['user_id'];
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BazoGRYzarka - Profil <?= htmlspecialchars($user['Nick']) ?></title>
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
    <?php if (isset($_SESSION['user']) && isset($_SESSION['user_id'])): ?>
        <img src="images/<?php echo htmlspecialchars($avatarTwoj ?? 'default.jpg'); ?>" alt="User Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="profile"><?php echo htmlspecialchars($_SESSION['user']); ?></a></span>
    <?php else: ?>
        <img src="images/default.jpg" alt="Default Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="login">Zaloguj się</a></span>
    <?php endif; ?>
</div>
    </div>
</nav>






<div class="profile-container">
    <div class="profile-section">
        <div class="profile-bubble">
            <div class="profile-header">
                <img src="images/<?= htmlspecialchars($user['ZdjecieProfilowe'] ?? 'default.jpg') ?>" alt="Profile Picture" class="profile-pic" width="100" height="100" />
                <h1><?= htmlspecialchars($user['Nick']) ?></h1> 
                <?php if ($is_own_profile): ?>
                    | <a href="logout.php">Wyloguj się</a>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <p><strong><?= htmlspecialchars($user['Imie'] . ' ' . $user['Nazwisko']) ?></strong></p>
                <p><?= htmlspecialchars($user['Kraj'] ?? 'Nie podano kraju') ?></p>
                <p>Użytkownik od: <?= htmlspecialchars(date('d-m-Y', strtotime($user['created_at']))) ?></p>
                <p class="bio"><?= htmlspecialchars($user['Opis'] ?? 'Brak opisu') ?></p>
            </div>
        </div>

        <?php if ($is_own_profile): ?>
        <div class="action-buttons">
            <a href="edit_profile" class="edit-btn">EDYTUJ PROFIL</a>
            <form method="post" action="delete_profile.php" onsubmit="return confirm('Czy na pewno chcesz usunąć swoje konto?');" style="display:inline;">
    <button type="submit" name="delete" class="delete-btn">USUŃ PROFIL</button>
</form>

            <button id="share-btn" data-userid="<?= (int)$_SESSION['user_id'] ?>">UDOSTĘPNIJ</button>


            <script>
  document.getElementById('share-btn').addEventListener('click', () => {
  const userId = document.getElementById('share-btn').dataset.userid;
  // Bazowy URL strony, np. profil:
  const baseUrl = window.location.origin + window.location.pathname; 

  // Tworzymy link z user_id jako GET parametrem
  const shareUrl = baseUrl + '?user_id=' + userId;

  // Kopiujemy do schowka
  navigator.clipboard.writeText(shareUrl).then(() => {
    alert('Link do udostępnienia: ' + shareUrl);
  }).catch(() => {
    alert('Nie udało się skopiować linku.');
  });
});

</script>

        </div>
        <?php endif; ?>
    </div>

    <div class="games-section">
        <h2>Posiadane gry:</h2>
        <div class="games-grid">
            <?php if (count($ownedGames) > 0): ?>
                <?php foreach ($ownedGames as $game): ?>
                    <?php 
                    $imageFileName = $game['zdjGlowne'] ?? '';
                    $imagePath = "images/" . $imageFileName;
                    if (!file_exists($imagePath) || empty($imageFileName)) {
                        $imagePath = "images/unknown.jpg";
                    }
                    ?>
                    <div class="game-card">
                        <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($game['Tytul']) ?>" />
                        <h3><?= htmlspecialchars($game['Tytul']) ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: white;">Brak posiadanych gier.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="footer">
    <a href="privacy">Polityka prywatności</a>, 2025 BazoGRYzarka Prawa Zastrzeżone &copy;
    </div>
</body>
</html>

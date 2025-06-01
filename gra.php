<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");

$game_id = $_GET['id'] ?? 0;

$user_id = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['user'] ?? 'Gość';  // fallback if not logged in
$avatar = $_SESSION['avatar'] ?? 'default.jpg';  // fallback avatar image
// Get game data
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


$stmt = $conn->prepare("SELECT Tytul, zdjGlowne, WebGLFolderName FROM Gra WHERE IdGry = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    echo "Gra nie została znaleziona.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($game['Tytul']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body
        {
            text-align: center;
            justify-content: center;
            align-items: center;

        }
        iframe {
            width: 100%;
            height: 90vh;
            border: none;
        }
    </style>
</head>
<body style="display: block;">
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
    <div id="gamePlayableContainer">
    <h1><?php echo htmlspecialchars($game['Tytul']); ?></h1>
    <iframe src="<?php echo htmlspecialchars($game['WebGLFolderName']); ?>/index.html"></iframe>
    </div>
    <div id="footer">
        <a href="privacy">Polityka prywatności</a>, 2025 BazoGRYzarka Prawa Zastrzeżone &copy;
    </div>
</body>
</html>

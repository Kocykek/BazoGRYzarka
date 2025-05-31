<?php
session_start();

// Connection
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Get game ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'] ?? 0;
$message = "";

$alreadyOwned = false;
if ($user_id && $id) {
    $checkStmt = $conn->prepare("SELECT 1 FROM UzytkownikGra WHERE Id_Uzytkownika = ? AND Id_Gry = ?");
    $checkStmt->bind_param("ii", $user_id, $id);
    $checkStmt->execute();
    $checkStmt->store_result();
    $alreadyOwned = $checkStmt->num_rows > 0;
    $checkStmt->close();
}



// Buy logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy']) && $user_id && $id) {
    $checkStmt = $conn->prepare("SELECT 1 FROM UzytkownikGra WHERE Id_Uzytkownika = ? AND Id_Gry = ?");
    $checkStmt->bind_param("ii", $user_id, $id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows === 0) {
        $insertStmt = $conn->prepare("INSERT INTO UzytkownikGra (Id_Uzytkownika, Id_Gry) VALUES (?, ?)");
        $insertStmt->bind_param("ii", $user_id, $id);
        $insertStmt->execute();
        $insertStmt->close();
        $message = "✅ Gra została przypisana do twojego konta!";
    }
    $checkStmt->close();
}

// Fetch game info
$stmt = $conn->prepare("SELECT G.krotkiOpis, G.zdjGlowne, G.dlugiOpis, G.Tytul, G.Wydawca, G.Producent, G.Cena, G.DataWydania, JS.CzyWindows, JS.CzyMac, JS.CzyLinux
                        FROM Gra G
                        JOIN JakieSystemy JS ON G.Id_JakieSystemy = JS.Id_JakieSystemy
                        WHERE G.IdGry = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?php echo $game ? htmlspecialchars($game['Tytul']) : "Gra nie znaleziona"; ?></title>
    <link rel="stylesheet" href="/BazoGRYzarka/styles.css">
</head>
<body style="display: block;">
<?php if ($game): ?>
    <nav class="navbar">
    <div class="nav-container">
        <h1 class="platform-name"><a href="main">BazoGRYzarka</a></h1>
        <div class="nav-links">
            <a href="/BazoGRYzarka/sklep">Sklep</a>
            <a href="/BazoGRYzarka/library">Biblioteka</a>
            <a href="/BazoGRYzarka/community">Społeczność</a>
            <a href="/BazoGRYzarka/contact">Pomoc Techniczna</a>
        </div>
        <div class="user-profile">
    <?php if (isset($_SESSION['user']) && isset($_SESSION['user_id'])): ?>
        <img src="images/<?php echo htmlspecialchars($_SESSION['avatar'] ?? 'default.jpg'); ?>" alt="User Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="profile.php"><?php echo htmlspecialchars($_SESSION['user']); ?></a></span>
    <?php else: ?>
        <img src="images/default.jpg" alt="Default Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="login.php">Zaloguj się</a></span>
    <?php endif; ?>
</div>
    </div>
</nav>

    <div class="steam-container">
        <div class="steam-header">
            <img src="/BazoGRYzarka/images/<?php echo htmlspecialchars($game['zdjGlowne']); ?>" width="800" alt="Okładka gry" class="game-banner">
            <div class="game-info">
                <h1><?php echo htmlspecialchars($game['Tytul']); ?></h1>
                <p class="price"><?php echo number_format($game['Cena'], 2); ?> PLN</p>
                <?php if (!empty($message)): ?>
                    <p class="purchase-message <?php echo strpos($message, '✅') !== false ? 'success' : 'info'; ?>">
                        <?php echo $message; ?>
                    </p>
                <?php endif; ?>

                <?php 
                if ($alreadyOwned)
                {
                    echo "<p class='purchase-message info'>Masz już tę grę.</p>";
                }
                else 
                {
                    echo "<form method='post'>
                        <input type='hidden' name='buy' value='1'>
                        <button type='submit' class='buy-button'>Kup Teraz</button>
                    </form>";
                }
                ?>
            </div>
        </div>

        <div class="steam-description">
            <h2>O grze</h2>
            <p><?php echo nl2br(htmlspecialchars($game['dlugiOpis'])); ?></p>
        </div>

        <div class="steam-details">
            <p><strong>Wydawca:</strong> <?php echo htmlspecialchars($game['Wydawca']); ?></p>
            <p><strong>Producent:</strong> <?php echo htmlspecialchars($game['Producent']); ?></p>
            <p><strong>Data wydania:</strong> <?php echo htmlspecialchars($game['DataWydania']); ?></p>
            <p><strong>Dostępne na: </strong>
                <?php
                $systems = [];
                if ($game['CzyWindows']) $systems[] = "<img src='/BazoGRYzarka/images/windows.png' width='30'>";
                if ($game['CzyMac'])     $systems[] = "<img src='/BazoGRYzarka/images/mac.png' width='30'>";
                if ($game['CzyLinux'])   $systems[] = "<img src='/BazoGRYzarka/images/linux.png' width='30'>";
                echo implode(" ", $systems);
                ?>
            </p>
        </div>

        <div class="back-link">
            <a href="/BazoGRYzarka/sklep">← Wróć do sklepu</a>
        </div>
    </div>
<?php else: ?>
    <p>Gra nie znaleziona.</p>
<?php endif; ?>
<div id="footer">
        <a href="privacy">Polityka prywatności</a>, 2025 BazoGRYzarka Prawa Zastrzeżone &copy;
    </div>
</body>
</html>

<?php
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
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
<body>
<?php if ($game): ?>
    <div id="navigationContainer">
        <a href="/BazoGRYzarka/sklep">Sklep</a>
        <a href="/BazoGRYzarka/biblioteka">Biblioteka</a>
        <a href="/BazoGRYzarka/spolecznosc">Społeczność</a>
        <a href="/BazoGRYzarka/wsparcie">Pomoc Techniczna</a>
    </div>

    
    <div class="steam-container">
        <div class="steam-header">
            <img src="/BazoGRYzarka/images/<?php echo htmlspecialchars($game['zdjGlowne']); ?>" width="800" alt="Okładka gry" class="game-banner">
            <div class="game-info">
                <h1><?php echo htmlspecialchars($game['Tytul']); ?></h1>
                <p class="price"><?php echo number_format($game['Cena'], 2); ?> PLN</p>
                <button class="buy-button">Kup Teraz</button>
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
                if ($game['CzyMac']) $systems[] = "<img src='/BazoGRYzarka/images/mac.png' width='30'>";
                if ($game['CzyLinux']) $systems[] = "<img src='/BazoGRYzarka/images/linux.png' width='30'>";
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
</body>
</html>

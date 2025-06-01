<?php
session_start();
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch users with their reviews
$sql = "
    SELECT 
        U.Id_Uzytkownika,
        U.Nick,
        U.Kraj,
        R.Tresc,
        R.Typ,
        R.Czas_gry,
        R.Data_wystawienie,
        G.Tytul
    FROM Uzytkownik U
    LEFT JOIN Recenzje R ON U.Id_Uzytkownika = R.Id_Uzytkownika
    LEFT JOIN Gra G ON R.Id_Gry = G.IdGry
    ORDER BY U.Nick ASC, R.Data_wystawienie DESC
";

$result = $conn->query($sql);

$users = [];

while ($row = $result->fetch_assoc()) {
    $userId = $row['Id_Uzytkownika'];
    if (!isset($users[$userId])) {
        $users[$userId] = [
            'Nick' => $row['Nick'],
            'Kraj' => $row['Kraj'],
            'Recenzje' => []
        ];
    }

    if ($row['Tresc']) {
        $users[$userId]['Recenzje'][] = [
            'Tytul' => $row['Tytul'],
            'Tresc' => $row['Tresc'],
            'Typ' => $row['Typ'],
            'Czas_gry' => $row['Czas_gry'],
            'Data' => $row['Data_wystawienie']
        ];
    }
}

$nick = $_SESSION['user'];

$stmt = $conn->prepare("SELECT ZdjecieProfilowe FROM Uzytkownik WHERE Nick = ?");
$stmt->bind_param("s", $nick);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $avatar = $row['ZdjecieProfilowe'] ?: 'default.jpg';
} else {
    $avatar = 'default.jpg';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Community</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body style="display: block;">
    <nav class="navbar">
    <div class="nav-container">
        <h1 class="platform-name"><a href="/BazoGRYzarka/main">BazoGRYzarka</a></h1>
        <div class="nav-links">
            <a href="/BazoGRYzarka/sklep">Sklep</a>
            <a href="/BazoGRYzarka/library">Biblioteka</a>
            <a href="/BazoGRYzarka/community">Społeczność</a>
            <a href="/BazoGRYzarka/contact">Pomoc Techniczna</a>
        </div>
        <div class="user-profile">
    <?php if (isset($_SESSION['user']) && isset($_SESSION['user_id'])): ?>
        <img src="/BazoGRYzarka/images/<?php echo htmlspecialchars($avatar ?? 'default.jpg'); ?>" alt="User Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="/BazoGRYzarka/profile"><?php echo htmlspecialchars($_SESSION['user']); ?></a></span>
    <?php else: ?>
        <img src="/BazoGRYzarka/images/default.jpg" alt="Default Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="/BazoGRYzarka/login">Zaloguj się</a></span>
    <?php endif; ?>
</div>
    </div>
</nav>
    <h1 style="display: flex;justify-content: center;align-items: center;margin: 15px;">🌍 Społeczność graczy</h1>
    <a style="width: 100%; height: 30px; background-color: lightblack; padding: 5px; display: flex; color: yellow; text-align: center; justify-content: center;" href="create_review">Napisz recenzję</a>
    <?php foreach ($users as $userId => $user): ?>
    <div class="user-box">
        <strong>
            <a style="color: white;" href="profile?user_id=<?= urlencode($userId) ?>">
                <?= htmlspecialchars($user['Nick']) ?>
            </a>
        </strong> (<?= htmlspecialchars($user['Kraj']) ?>)
        <?php if (!empty($user['Recenzje'])): ?>
            <div class="reviews">
                <h4>Recenzje:</h4>
                <?php foreach ($user['Recenzje'] as $rev): ?>
                    <div class="review">
                        <strong><?= htmlspecialchars($rev['Tytul']) ?></strong> - <?= htmlspecialchars($rev['Typ']) ?>⭐
                        <div><em><?= nl2br(htmlspecialchars($rev['Tresc'])) ?></em></div>
                        <div>Czas gry: <?= htmlspecialchars($rev['Czas_gry']) ?> h | <?= htmlspecialchars($rev['Data']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p><em>Brak recenzji.</em></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<div id="footer">
        <a href="privacy">Polityka prywatności</a>, 2025 BazoGRYzarka Prawa Zastrzeżone &copy;
    </div>
</body>
</html>

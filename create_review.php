<?php
session_start();
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'] ?? 0;
$message = "";

if (!$user_id) {
    die("Musisz być zalogowany, aby wystawić recenzję.");
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
// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = intval($_POST['game_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $content = trim($_POST['review'] ?? "");
    $playtime = floatval($_POST['playtime'] ?? 0);
    $now = date('Y-m-d');

    if ($game_id && $rating >= 1 && $rating <= 5 && $content) {
        // Check if review already exists
        $check = $conn->prepare("SELECT 1 FROM Recenzje WHERE Id_Gry = ? AND Id_Uzytkownika = ?");
        $check->bind_param("ii", $game_id, $user_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "⚠️ Już wystawiłeś recenzję tej gry.";
        } else {
            // Insert new review
            $stmt = $conn->prepare("INSERT INTO Recenzje (Id_Gry, Typ, Tresc, Id_Uzytkownika, Data_wystawienie, Czas_gry) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisisd", $game_id, $rating, $content, $user_id, $now, $playtime);
            if ($stmt->execute()) {
                $message = "✅ Recenzja została zapisana!";
            } else {
                $message = "❌ Błąd przy zapisie recenzji.";
            }
            $stmt->close();
        }

        $check->close();
    } else {
        $message = "⚠️ Wypełnij poprawnie wszystkie pola.";
    }
}

// Get games owned by user
$games = [];
$stmt = $conn->prepare("
    SELECT G.IdGry, G.Tytul 
    FROM Gra G
    JOIN UzytkownikGra UG ON UG.Id_Gry = G.IdGry
    WHERE UG.Id_Uzytkownika = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $games[] = $row;
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wystaw recenzję</title>
    <link rel="stylesheet" href="/BazoGRYzarka/styles.css">
</head>
<body style="display: block;">
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
        <img src="/BazoGRYzarka/images/<?php echo htmlspecialchars($avatar ?? '/BazoGRYzarka/images/default.jpg'); ?>" alt="User Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="/BazoGRYzarka/profile"><?php echo htmlspecialchars($_SESSION['user']); ?></a></span>
    <?php else: ?>
        <img src="/BazoGRYzarka/images/default.jpg" alt="Default Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="/BazoGRYzarka/login">Zaloguj się</a></span>
    <?php endif; ?>
</div>
    </div>
</nav>
    <div class="review-container">
        <h1>Wystaw recenzję</h1>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (count($games) > 0): ?>
            <form method="post">
                <label for="game_id">Wybierz grę:</label>
                <select name="game_id" id="game_id" required>
                    <option value="">-- Wybierz --</option>
                    <?php foreach ($games as $game): ?>
                        <option value="<?php echo $game['IdGry']; ?>"><?php echo htmlspecialchars($game['Tytul']); ?></option>
                    <?php endforeach; ?>
                </select>
<br>
                <label for="rating">Ocena (1–5 gwiazdek):</label><br>
                <select name="rating" id="rating" required>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> ★</option>
                    <?php endfor; ?>
                </select>
                        <br>
                <label for="playtime">Czas gry (w godzinach):</label><br>
                <input type="number" name="playtime" id="playtime" step="0.1" min="0" required><br>

                <label for="review">Treść recenzji:</label><br>
                <textarea name="review" id="review" rows="5" required></textarea>

                <button type="submit">Wyślij recenzję</button>
            </form>
        <?php else: ?>
            <p>Nie posiadasz jeszcze żadnej gry, aby wystawić recenzję.</p>
        <?php endif; ?>
    </div>
    
</body>
</html>

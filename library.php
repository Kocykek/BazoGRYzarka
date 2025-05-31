<?php
session_start();

// Connection
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['user'] ?? 'Gość';  // fallback if not logged in
$avatar = $_SESSION['avatar'] ?? 'default.jpg';  // fallback avatar image

$search = $_GET['search'] ?? '';
$search_param = '%' . $search . '%';

if (!empty($search)) {
    $query = $conn->prepare("
        SELECT G.Tytul, G.zdjGlowne, G.IdGry
        FROM UzytkownikGra UG
        JOIN Gra G ON UG.Id_Gry = G.IdGry
        WHERE UG.Id_Uzytkownika = ? AND G.Tytul LIKE ?
    ");
    $query->bind_param("is", $user_id, $search_param);
} else {
    $query = $conn->prepare("
        SELECT G.Tytul, G.zdjGlowne, G.IdGry
        FROM UzytkownikGra UG
        JOIN Gra G ON UG.Id_Gry = G.IdGry
        WHERE UG.Id_Uzytkownika = ?
    ");
    $query->bind_param("i", $user_id);
}

$query->execute();
$result = $query->get_result();


?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazoGRYzarka - Biblioteka</title>
    <link rel="stylesheet" href="biblioteka.css">
</head>
<body>
    <!-- Navigation Bar (same as before) -->
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
        <img src="images/<?php echo htmlspecialchars($_SESSION['avatar'] ?? 'default.jpg'); ?>" alt="User Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="profile.php"><?php echo htmlspecialchars($_SESSION['user']); ?></a></span>
    <?php else: ?>
        <img src="images/default.jpg" alt="Default Avatar" class="user-avatar" width="40" height="40" />
        <span class="username"><a href="login.php">Zaloguj się</a></span>
    <?php endif; ?>
</div>
    </div>
</nav>

    <!-- Main Content -->
    <div class="library-container">
        <!-- Search Section -->
        <div class="search-section">
		<div class="search-bg"></div>
		<div class="search-content">
            <h1>WYSZUKAJ GRĘ</h1>
            <form method="GET" action="">
    <div class="search-bar">
        <input type="text" name="search" placeholder="Wpisz nazwę gry..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="search-btn" type="submit">SZUKAJ</button>
    </div>
</form>

			</div>
        </div>

        <!-- Games Library Grid -->
        <div class="games-library">
            <h2>Posiadane gry:</h2>
            
            <div class="games-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="game-card">
                <div class="game-card-img-container">
                    <a href="gra.php?id=<?php echo $row['IdGry']; ?>">
                        <img src="/BazoGRYzarka/images/<?php echo htmlspecialchars($row['zdjGlowne']); ?>" alt="<?php echo htmlspecialchars($row['Tytul']); ?>">
                    </a>
                </div>
                <h3><?php echo htmlspecialchars($row['Tytul']); ?></h3>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Nie posiadasz jeszcze żadnych gier.</p>
    <?php endif; ?>
</div>
        </div>
    </div>
    <div id="footer">
        <a href="privacy">Polityka prywatności</a>, 2025 BazoGRYzarka Prawa Zastrzeżone &copy;
    </div>
</body>
</html>
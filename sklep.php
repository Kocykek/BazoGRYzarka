<?php
session_start();
?>
<?php

// połączenie
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// zbierz wszystkie gry z tabeli
// $query = "SELECT G.zdjGlowne, G.IdGry, G.Tytul, G.Wydawca, G.Producent, G.Cena, G.DataWydania, JS.CzyWindows, JS.CzyMac, JS.CzyLinux
//           FROM Gra G
//           JOIN JakieSystemy JS ON G.Id_JakieSystemy = JS.Id_JakieSystemy";
// $result = $conn->query($query);
$q = $_GET['q'] ?? '';
$q_escaped = $conn->real_escape_string($q);

$systems = $_GET['system'] ?? []; // array or empty

// Base query
$sql = "SELECT G.zdjGlowne, G.IdGry, G.Tytul, G.Wydawca, G.Producent, G.Cena, G.DataWydania, 
        JS.CzyWindows, JS.CzyMac, JS.CzyLinux
        FROM Gra G
        JOIN JakieSystemy JS ON G.Id_JakieSystemy = JS.Id_JakieSystemy
        WHERE 1=1";

// Filter by search term (title)
if ($q !== '') {
    $sql .= " AND G.Tytul LIKE '%$q_escaped%'";
}

// Filter by systems if any selected
if (!empty($systems)) {
    $system_conditions = [];

    if (in_array('Windows', $systems)) $system_conditions[] = "JS.CzyWindows = 1";
    if (in_array('Mac', $systems))     $system_conditions[] = "JS.CzyMac = 1";
    if (in_array('Linux', $systems))   $system_conditions[] = "JS.CzyLinux = 1";

    if (!empty($system_conditions)) {
        // Join with OR because game should be on ANY selected system
        $sql .= " AND (" . implode(" OR ", $system_conditions) . ")";
    }
}

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep - Lista Gier</title>
    <link rel="stylesheet" href="styles.css">   
</head>
<body style="display: block;">
    <div id="navigationContainer">
        <h4>BazoGRYzarka</h4> <a href="/BazoGRYzarka/sklep">Sklep</a>
        <a href="/BazoGRYzarka/biblioteka">Biblioteka</a>
        <a href="/BazoGRYzarka/spolecznosc">Społeczność</a>
        <a href="/BazoGRYzarka/wsparcie">Pomoc Techniczna</a>
        <?php
            if (isset($_SESSION['user'])) {
                echo "<a href='dashboard.php'><div id='userSmall'><img src='/BazoGRYzarka/images/default.jpg' width='30' height='30'>" . htmlspecialchars($_SESSION['user']) . "</p></div></a>";
            }   else {
                echo "<div id='userSmall'><p>Nie jesteś zalogowany.</p></div>";
            }
        ?>
    </div>
    <div id="searchBar">
    <form method="GET" action="/BazoGRYzarka/sklep" class="search-form">
    <input type="text" name="q" placeholder="Szukaj gry..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    <br>
    <label><input type="checkbox" name="system[]" value="Windows" <?= (isset($_GET['system']) && in_array('Windows', $_GET['system'])) ? 'checked' : '' ?>> Windows</label>
    <label><input type="checkbox" name="system[]" value="Mac" <?= (isset($_GET['system']) && in_array('Mac', $_GET['system'])) ? 'checked' : '' ?>> Mac</label>
    <label><input type="checkbox" name="system[]" value="Linux" <?= (isset($_GET['system']) && in_array('Linux', $_GET['system'])) ? 'checked' : '' ?>> Linux</label>
    <br>
    <button type="submit">Szukaj</button>
</form>
    <h2>...lub przeglądaj wszystkie gry dostępne na platformie!</h2>
</div>
    <div class="game-container">
        <?php
        
        if ($result->num_rows > 0) {
            // przjedz przez wszystkie rekordy
            while ($row = $result->fetch_assoc()) {
                $systems = [];
                if ($row['CzyWindows'] == 1) $systems[] = "Windows";
                if ($row['CzyMac'] == 1) $systems[] = "Mac";
                if ($row['CzyLinux'] == 1) $systems[] = "Linux";
                $system_list = implode(", ", $systems);
                $thumb = !empty($row['zdjGlowne']) ? htmlspecialchars($row['zdjGlowne']) : 'unknown.jpg';

                echo "
<div class='steam-game-card'>
    <img class='game-thumb' src='/BazoGRYzarka/images/$thumb' width='120' height='70' alt='Thumbnail'>
    <div class='game-info'>
        <div class='game-titleCard'><h3 class='game-title'><a href='sklep/id/" . $row['IdGry'] . "'>" . htmlspecialchars($row['Tytul']) . "</a></h3></div>
        <div class='game-priceCard'><p class='price'>" . number_format($row['Cena'], 2) . " PLN</p></div>
    </div>
</div>";

            }
        } else {
            echo "<p>Brak gier w sklepie.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>

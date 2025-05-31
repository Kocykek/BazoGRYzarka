<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

$pegiFilters = [
    'pegi_violence' => 'CzyPrzemoc',
    'pegi_drugs' => 'CzyNarkotyki',
    'pegi_sex' => 'CzyTresciSeksualne',
    'pegi_profanity' => 'CzyWulgarnyJezyk',
    'pegi_inapp' => 'CzyZakupyWGrze',
    'pegi_fear' => 'CzyStrach',
    'pegi_discrimination' => 'CzyDyskryminacja'
];

// Base query
$sql = "SELECT DISTINCT G.zdjGlowne, G.IdGry, G.Tytul, G.Wydawca, G.Producent, G.Cena, G.DataWydania, 
               JS.CzyWindows, JS.CzyMac, JS.CzyLinux, P.Rating
        FROM Gra G
        JOIN JakieSystemy JS ON G.Id_JakieSystemy = JS.Id_JakieSystemy
        JOIN Pegi P ON G.Id_Pegi = P.Id_Pegi
        LEFT JOIN TagGry TG ON G.IdGry = TG.Id_Gry
        LEFT JOIN Tagi T ON TG.Id_tagu = T.Id_tagu
        WHERE 1=1";

// Existing filters here: search by title, system, PEGI rating, tags...

// Add PEGI attribute filters:
foreach ($pegiFilters as $inputName => $column) {
    if (!empty($_GET[$inputName])) {
        $sql .= " AND P.$column = 1";
    }
}

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

if (!empty($_GET['pegi'])) {
    $pegi = (int) $_GET['pegi'];
    $sql .= " AND P.Rating = $pegi";
}

// Filter by tags
if (!empty($_GET['tags'])) {
    $tags = array_map('intval', $_GET['tags']);
    $tag_ids_str = implode(',', $tags);

    // Game must have AT LEAST ONE of the selected tags
    $sql .= " AND TG.Id_tagu IN ($tag_ids_str)";
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

    <div id="searchBar">
    <form method="GET" action="/BazoGRYzarka/sklep" class="search-form">
    <input type="text" name="q" placeholder="Szukaj gry..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    <br>
    <label><input type="checkbox" name="system[]" value="Windows" <?= (isset($_GET['system']) && in_array('Windows', $_GET['system'])) ? 'checked' : '' ?>> Windows</label>
    <label><input type="checkbox" name="system[]" value="Mac" <?= (isset($_GET['system']) && in_array('Mac', $_GET['system'])) ? 'checked' : '' ?>> Mac</label>
    <label><input type="checkbox" name="system[]" value="Linux" <?= (isset($_GET['system']) && in_array('Linux', $_GET['system'])) ? 'checked' : '' ?>> Linux</label>
    <br>
    <select name="pegi">
    <option value="">-- PEGI --</option>
    <option value="3" <?= ($_GET['pegi'] ?? '') == 3 ? 'selected' : '' ?>>PEGI 3</option>
    <option value="7" <?= ($_GET['pegi'] ?? '') == 7 ? 'selected' : '' ?>>PEGI 7</option>
    <option value="12" <?= ($_GET['pegi'] ?? '') == 12 ? 'selected' : '' ?>>PEGI 12</option>
    <option value="16" <?= ($_GET['pegi'] ?? '') == 16 ? 'selected' : '' ?>>PEGI 16</option>
    <option value="18" <?= ($_GET['pegi'] ?? '') == 18 ? 'selected' : '' ?>>PEGI 18</option>
</select>

  <legend>Filtruj po treściach PEGI:</legend>
  <label><input type="checkbox" name="pegi_violence" value="1" <?= isset($_GET['pegi_violence']) ? 'checked' : '' ?>> Przemoc</label>
  <label><input type="checkbox" name="pegi_drugs" value="1" <?= isset($_GET['pegi_drugs']) ? 'checked' : '' ?>> Narkotyki</label>
  <label><input type="checkbox" name="pegi_sex" value="1" <?= isset($_GET['pegi_sex']) ? 'checked' : '' ?>> Treści seksualne</label>
  <label><input type="checkbox" name="pegi_profanity" value="1" <?= isset($_GET['pegi_profanity']) ? 'checked' : '' ?>> Wulgarny język</label>
  <label><input type="checkbox" name="pegi_inapp" value="1" <?= isset($_GET['pegi_inapp']) ? 'checked' : '' ?>> Zakupy w grze</label>
  <label><input type="checkbox" name="pegi_fear" value="1" <?= isset($_GET['pegi_fear']) ? 'checked' : '' ?>> Strach</label>
  <label><input type="checkbox" name="pegi_discrimination" value="1" <?= isset($_GET['pegi_discrimination']) ? 'checked' : '' ?>> Dyskryminacja</label>


<?php
$tag_result = $conn->query("SELECT Id_tagu, Nazwa FROM Tagi");
while ($tag = $tag_result->fetch_assoc()):
?>
    <label>
        <input type="checkbox" name="tags[]" value="<?= $tag['Id_tagu'] ?>" <?= (isset($_GET['tags']) && in_array($tag['Id_tagu'], $_GET['tags'])) ? 'checked' : '' ?>>
        <?= htmlspecialchars($tag['Nazwa']) ?>
    </label>
<?php endwhile; ?>
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
    <div id="footer">
        <a href="privacy">Polityka prywatności</a>, 2025 BazoGRYzarka Prawa Zastrzeżone &copy;
    </div>
</body>
</html>

<?php
// połączenie
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// zbierz wszystkie gry z tabeli
$query = "SELECT G.Tytul, G.Wydawca, G.Producent, G.Cena, G.DataWydania, JS.CzyWindows, JS.CzyMac, JS.CzyLinux
          FROM Gra G
          JOIN JakieSystemy JS ON G.Id_JakieSystemy = JS.Id_JakieSystemy";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep - Lista Gier</title>
    <link rel="stylesheet" href="styles.css">   
</head>
<body>
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

                echo "<div class='game-card'>
                        <h3>" . htmlspecialchars($row['Tytul']) . "</h3>
                        <p><strong>Wydawca:</strong> " . htmlspecialchars($row['Wydawca']) . "</p>
                        <p><strong>Producent:</strong> " . htmlspecialchars($row['Producent']) . "</p>
                        <p><strong>Systemy:</strong> $system_list</p>
                        <p><strong>Data Wydania:</strong> " . htmlspecialchars($row['DataWydania']) . "</p>
                        <p class='price'>" . number_format($row['Cena'], 2) . " PLN</p>
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

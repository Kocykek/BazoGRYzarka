<?php
// połączenie
$conn = new mysqli("localhost", "root", "newpassword", "BazoGRYzarka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tytul = $_POST['tytul'];
    $wydawca = $_POST['wydawca'];
    $producent = $_POST['producent'];
    $cena = $_POST['cena'];
    $data_wydania = $_POST['data_wydania'];


    $conditions = [];

    // jeśli te checkboxy będą zaznaczone to 1, jak nie to 0
    if (isset($_POST['linux']) && $_POST['linux'] == 1) {
        $conditions[] = "CzyLinux = 1";
    } else {
        $conditions[] = "CzyLinux = 0";
    }

    if (isset($_POST['windows']) && $_POST['windows'] == 1) {
        $conditions[] = "CzyWindows = 1";
    } else {
        $conditions[] = "CzyWindows = 0"; 
    }

    if (isset($_POST['mac']) && $_POST['mac'] == 1) {
        $conditions[] = "CzyMac = 1";
    } else {
        $conditions[] = "CzyMac = 0";
    }

    // poka błąd tak o jak błąð
    if (empty($conditions)) {
        echo "<p style='color:red;'>Wybierz przynajmniej jeden system!</p>";
    } else {
        // AND żeby było ze WHERE LINUX = 0 czy coś AND WIDNWOS = 0 czy 1 czy coś AND MAC = 0/1 ! 
        $whereClause = implode(" AND ", $conditions);
        
        // tu wez zobacz czy cos spelnia
        $query = "SELECT Id_JakieSystemy FROM JakieSystemy WHERE " . $whereClause;
        $result = $conn->query($query);

        // zoba czy takie scenario coś istnieje wogóle
        if ($result->num_rows > 0) {
            // wez rekord i id tego jakiesystemy xd czy coś
            $row = $result->fetch_assoc();
            $id_systemu = $row['Id_JakieSystemy'];

            // ostatecznie insert C:
            $stmt = $conn->prepare("INSERT INTO Gra (Tytul, Wydawca, Producent, Id_JakieSystemy, Cena, DataWydania) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssids", $tytul, $wydawca, $producent, $id_systemu, $cena, $data_wydania);
            
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Gra dodana pomyślnie!</p>";
            } else {
                echo "<p style='color:red;'>Błąd: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p style='color:red;'>Nie znaleziono pasujących systemów!</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Admina - Dodaj Grę</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <h2>Dodaj Nową Grę</h2>
        <input type="text" name="tytul" placeholder="Tytuł gry" required>
        <input type="text" name="wydawca" placeholder="Wydawca" required>
        <input type="text" name="producent" placeholder="Producent" required>
        <input type="number" name="cena" step="0.01" placeholder="Cena" required>
        <input type="date" name="data_wydania" required>

        <h3>Wybierz Systemy</h3>
        <label>
            <input type="checkbox" name="windows" value="1"> Windows
        </label>
        <label>
            <input type="checkbox" name="linux" value="1"> Linux
        </label>
        <label>
            <input type="checkbox" name="mac" value="1"> Mac
        </label>

        <button type="submit">Dodaj Grę</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>

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

    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    $czyPrzemoc = isset($_POST['czyPrzemoc']) ? 1 : 0;
    $czyNarkotyki = isset($_POST['czyNarkotyki']) ? 1 : 0;
    $czyTresciSeksualne = isset($_POST['czyTresciSeksualne']) ? 1 : 0;
    $czyWulgaryzmy = isset($_POST['czyWulgaryzmy']) ? 1 : 0;
    $czyZakupy = isset($_POST['czyZakupy']) ? 1 : 0;
    $czyStrach = isset($_POST['czyStrach']) ? 1 : 0;
    $czyDyskryminacja = isset($_POST['czyDyskryminacja']) ? 1 : 0;

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
        
        $pegiQuery = "SELECT Id_Pegi FROM Pegi WHERE 
            Rating = $rating AND 
            CzyPrzemoc = $czyPrzemoc AND 
            CzyNarkotyki = $czyNarkotyki AND 
            CzyTresciSeksualne = $czyTresciSeksualne AND 
            CzyWulgarnyJezyk = $czyWulgaryzmy AND 
            CzyZakupyWGrze = $czyZakupy AND 
            CzyStrach = $czyStrach AND 
            CzyDyskryminacja = $czyDyskryminacja";
        $pegiResult = $conn->query($pegiQuery);

        if ($pegiResult->num_rows > 0) {
            $id_pegi = $pegiResult->fetch_assoc()['Id_Pegi'];
        } else {
            // Wstaw nową kombinację
            $stmt = $conn->prepare("INSERT INTO Pegi (Rating, CzyPrzemoc, CzyNarkotyki, CzyTresciSeksualne, CzyWulgarnyJezyk, CzyZakupyWGrze, CzyStrach, CzyDyskryminacja) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiiiii", $rating, $czyPrzemoc, $czyNarkotyki, $czyTresciSeksualne, $czyWulgaryzmy, $czyZakupy, $czyStrach, $czyDyskryminacja);
            if ($stmt->execute()) {
                $id_pegi = $conn->insert_id;
            } else {
                echo "<p style='color:red;'>Błąd PEGI: " . $stmt->error . "</p>";
                exit;
            }
            $stmt->close();
        }

        // zoba czy takie scenario coś istnieje wogóle
        if ($result->num_rows > 0) {
            // wez rekord i id tego jakiesystemy xd czy coś
            $row = $result->fetch_assoc();
            $id_systemu = $row['Id_JakieSystemy'];

            // ostatecznie insert C:
            $stmt = $conn->prepare("INSERT INTO Gra (Tytul, Wydawca, Producent, Id_JakieSystemy, Id_Pegi, Cena, DataWydania) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiss", $tytul, $wydawca, $producent, $id_systemu, $id_pegi, $cena, $data_wydania);
            
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
            <input id="systemChecks" type="checkbox" name="windows" value="1"> Windows
        </label>
        <label>
            <input id="systemChecks" type="checkbox" name="linux" value="1"> Linux
        </label>
        <label>
            <input id="systemChecks" type="checkbox" name="mac" value="1"> Mac
        </label>

        <h3>Oznaczenia PEGI (Opcjonalne)</h3>
        <div id="pegiContainer">
                <input id="pegiChecks" type="input"placeholder="Rating (3,7,12,16,18)" name="rating" value="1"><br>
            <div id="onePegiContain"> 
                <input id="pegiChecks" type="checkbox" name="czyPrzemoc" value="1"> <img src="images/przemoc.jpg" width="50"><br>
            </div>
            <div id="onePegiContain">
                <input id="pegiChecks" type="checkbox" name="czyNarkotyki" value="1"> <img src="images/uzywki.jpg" width="50"><br>
            </div>
            <div id="onePegiContain">
                <input id="pegiChecks" type="checkbox" name="czyTresciSeksualne" value="1"> <img src="images/tresciseksualne.jpg" width="50"><br>
            </div>
            <div id="onePegiContain">
                <input id="pegiChecks" type="checkbox" name="czyWulgaryzmy" value="1"> <img src="images/czywulgaryzmy.jpg" width="50"><br>
            </div>
            <div id="onePegiContain">
                <input id="pegiChecks" type="checkbox" name="czyZakupy" value="1"> <img src="images/czyZakupy.jpg" width="50"><br>
            </div>
            <div id="onePegiContain">
                <input id="pegiChecks" type="checkbox" name="czyStrach" value="1"> <img src="images/strach.jpg" width="50"><br>
            </div>
            <div id="onePegiContain">
                <input id="pegiChecks" type="checkbox" name="czyDyskryminacja" value="1"> <img src="images/dyskryminacja.jpg" width="50"><br>
            </div>
        </div>
            <button type="submit">Dodaj Grę</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>

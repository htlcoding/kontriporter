<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: anmeldung.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_option'])) {
        $selectedOptionIndex = $_POST['selected_option'];

        try {
            $db = new mysqli('localhost', 'root', '', 'Database1');
            if ($db->connect_error) {
                die('Verbindungsfehler: ' . $db->connect_error);
            }

            $stmt = $db->prepare('UPDATE kontrollliste SET report_count = report_count + 1 WHERE id = ?');
            $stmt->bind_param('i', $selectedOptionIndex);
            $stmt->execute();

            $stmt = $db->prepare('UPDATE users SET credits = credits + 5 WHERE username = ?');
            $stmt->bind_param('s', $_SESSION['username']);
            $stmt->execute();

            echo 'Text erfolgreich gemeldet.';
        } catch (Exception $e) {
            echo 'Fehler beim Speichern des gemeldeten Textes.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kontri Melden</title>
</head>
<body>
    <h1>Melde einen Kontri</h1>
    <form method="post" action="">
        <label for="selected_option">Option auswählen:</label><br>
        <select name="selected_option" id="selected_option" required>
            <?php
            try {
                $db = new mysqli('localhost', 'root', '', 'Database1');
                if ($db->connect_error) {
                    die('Verbindungsfehler: ' . $db->connect_error);
                }

                // Fetch options from the database
                $optionsQuery = $db->query('SELECT * FROM kontrollliste');
                while ($option = $optionsQuery->fetch_assoc()) {
                    echo '<option value="' . $option['id'] . '">' . htmlspecialchars($option['description']) . '</option>';
                }
            } catch (Exception $e) {
                echo 'Fehler beim Abrufen der Optionen.';
            }
            ?>
        </select><br>
        <input type="submit" name="submit" value="Melden">
    </form>
</body>
</html>

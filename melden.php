<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kontri Melden</title>
</head>
<body>
    <?php
    // Starte die Sitzung
    session_start();

    // Überprüfe, ob der Benutzer angemeldet ist
    if (!isset($_SESSION['username'])) {
        header('Location: anmeldung.php');
        exit;
    }

    // Read JSON options from external file
    $jsonFile = './temp/liste.json';
    $jsonContents = file_get_contents($jsonFile);
    $jsonOptions = json_decode($jsonContents, true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Überprüfe, ob die ausgewählte Option vorhanden ist
        if (isset($_POST['selected_option'])) {
            $selectedOptionIndex = $_POST['selected_option'];

            // Validate the selected option index
            if (array_key_exists($selectedOptionIndex, $jsonOptions)) {
                $selectedOption = $jsonOptions[$selectedOptionIndex];
                $text = $selectedOption[2];

                // Connect to the database securely
                try {
                    $db = new mysqli('hostname', 'username', 'password', 'database_name');
                    if ($db->connect_error) {
                        die('Verbindungsfehler: ' . $db->connect_error);
                    }
                } catch (Exception $e) {
                    header('Location: servers_down.html');
                    exit;
                }

                // Prepare and execute SQL statement to insert the reported text
                $stmt = $db->prepare('INSERT INTO gemeldete_texte (username, gemeldeter_text) VALUES (?, ?)');
                $stmt->bind_param('ss', $_SESSION['username'], $text);

                if ($stmt->execute()) {
                    echo 'Text erfolgreich gemeldet: ' . htmlspecialchars($text);
                } else {
                    echo 'Fehler beim Speichern des gemeldeten Textes.';
                }

                // Close the database connection
                $stmt->close();
                $db->close();
            } else {
                echo 'Ungültige Option ausgewählt.';
            }
        }
    }
    ?>

    <h1>Melde einen Kontri</h1>
    <form method="post" action="">
        <label for="selected_option">Option auswählen:</label><br>
        <select name="selected_option" id="selected_option" required>
            <?php foreach ($jsonOptions as $index => $option) { ?>
                <option value="<?php echo $index; ?>">
                    <?php echo htmlspecialchars($option[2]); ?> (<?php echo htmlspecialchars($option[1]); ?>)
                </option>
            <?php } ?>
        </select><br>
        <input type="submit" name="submit" value="Melden">
    </form>
</body>
</html>

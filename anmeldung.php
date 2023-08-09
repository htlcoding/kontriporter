<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Anmeldung</title>
</head>
<body>
    <form method="post" action="">
        <label for="username">Benutzername:</label><br>
        <input type="text" name="username" id="username"><br>
        <label for="password">Passwort:</label><br>
        <input type="password" name="password" id="password"><br>
        <input type="submit" name="login" value="Anmelden">
    </form>

    <p>
    <?php
        // Verbindung zur Datenbank herstellen
        $db = new mysqli("hostname", "username", "password", "database_name");

        // Überprüfen, ob die Verbindung erfolgreich war
        if ($db->connect_error) {
            die("Fehler beim Verbinden zur Datenbank: " . $db->connect_error);
        }

        // Überprüfen, ob das Formular abgeschickt wurde
        if (isset($_POST["login"])) {
            // Benutzerdaten aus dem Formular holen
            $username = $_POST["username"];
            $password = $_POST["password"];

            // Überprüfen, ob der Benutzername und das Passwort korrekt sind
            $stmt = $db->prepare("SELECT * FROM users WHERE username=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user["password"])) {
                    echo "Anmeldung erfolgreich";
                    // Hier können weitere Aktionen durchgeführt werden, z.B. eine Session starten
                } else {
                    echo "Falscher Benutzername oder Passwort";
                }
            } else {
                echo "Falscher Benutzername oder Passwort";
            }
        }
    ?>
    </p>

    <p>Noch nicht registriert? <a href="/registrierung.php">Registrieren</a></p>
</body>
</html>
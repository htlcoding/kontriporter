<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
</head>
<body>
    <form method="post" action="">
        <label for="username">Benutzername:</label><br>
        <input type="text" name="username" id="username"><br>
        <label for="password">Passwort:</label><br>
        <input type="password" name="password" id="password"><br>
        <label for="password2">Passwort wiederholen:</label><br>
        <input type="password" name="password2" id="password2"><br>
        <label for="email">E-Mail:</label><br>
        <input type="email" name="email" id="email"><br>
        <input type="submit" name="register" value="Registrieren">
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
        if (isset($_POST["register"])) {
            // Benutzerdaten aus dem Formular holen
            $username = $_POST["username"];
            $password = $_POST["password"];
            $password2 = $_POST["password2"];
            $email = $_POST["email"];

            // Überprüfen, ob alle Felder ausgefüllt wurden
            if (empty($username) || empty($password) || empty($password2) || empty($email)) {
                echo "Bitte alle Felder ausfüllen";
            } else {
                // Überprüfen, ob die Passwörter übereinstimmen
                if ($password != $password2) {
                    echo "Die Passwörter stimmen nicht überein";
                } else {
                    // Überprüfen, ob der Benutzername bereits existiert
                    $stmt = $db->prepare("SELECT * FROM users WHERE username=?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo "Benutzername bereits vergeben";
                    } else {
                        // Benutzer in die Datenbank einfügen
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $username, $password_hash, $email);
                        if ($stmt->execute()) {
                            echo "Registrierung erfolgreich";
                        } else {
                            echo "Fehler bei der Registrierung";
                        }
                    }
                }
            }
        }
    ?>
    </p>

    <p>Hast du bereits ein Konto? <a href="/anmeldung.php">Anmelden</a></p>
</body>
</html>
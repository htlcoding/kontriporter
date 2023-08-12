<?php
require_once './scripts/user_validation.php';
session_start();
if (CheckLoggedIn()) {
    header('Location: ./index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
</head>
<body>
    <form method="post" action="">
        <label for="username">Benutzername (3-25 Zeichen):</label><br>
        <input type="text" name="username" id="username" minlength="3" maxlength="25" required><br>
        <label for="password">Passwort (5-100 Zeichen, mindestens 1 Zahl):</label><br>
        <input type="password" name="password" id="password" minlength="5" maxlength="100" required><br>
        <label for="password2">Passwort wiederholen:</label><br>
        <input type="password" name="password2" id="password2" minlength="5" maxlength="100" required><br>
        <label for="email">E-Mail:</label><br>
        <input type="email" name="email" id="email" required><br>
        <input type="submit" name="register" value="Registrieren">
    </form>
    <p>Nachdem du dich registrierst, wirst du dich anmelden müssen.</p>
    <p>Hast du bereits ein Konto? <a href="./anmeldung.php">Anmelden</a></p>
    <?php
    // Connect to the database securely
    try {
        $db = new mysqli('localhost', 'root', '', 'Database1');
        if ($db->connect_error) {
            header('Location: servers_down.html');
        }
    } catch (Exception $e) {
        header('Location: servers_down.html');
    }

    // Check if the register form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        if (empty($username) || empty($password) || empty($password2) || !$email) {
            echo 'Bitte alle Felder ausfüllen';
        } else {
            if (strlen($username) < 3 || strlen($username) > 25) {
                echo 'Benutzername muss zwischen 3 und 25 Zeichen lang sein';
            } else if ($password !== $password2) {
                echo 'Die Passwörter stimmen nicht überein';
            } else if (strlen($password) < 5 || strlen($password) > 100 || !preg_match('/\d/', $password)) {
                echo 'Passwort muss zwischen 5 und 100 Zeichen lang sein und mindestens eine Zahl enthalten';
            } else {
                $stmt = $db->prepare('SELECT * FROM users WHERE username=?');
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo 'Benutzername bereits vergeben';
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
                    $stmt->bind_param('sss', $username, $password_hash, $email);

                    if ($stmt->execute()) {
                        // Insert the user into the ranking table with initial credit value
                        $initialCredit = 0; // Set the initial credit value
                        $stmt = $db->prepare('INSERT INTO ranking (username, credit) VALUES (?, ?)');
                        $stmt->bind_param('si', $username, $initialCredit);
                        $stmt->execute();

                        echo 'Registrierung erfolgreich';
                        header('Location: anmeldung.php');
                        exit;
                    } else {
                        echo 'Fehler bei der Registrierung';
                    }
                }
            }
        }
    }
    ?>
</body>
</html>
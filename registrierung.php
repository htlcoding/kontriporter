<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
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
        <label for="username">Benutzername:</label><br>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Passwort:</label><br>
        <input type="password" name="password" id="password" required><br>
        <label for="password2">Passwort wiederholen:</label><br>
        <input type="password" name="password2" id="password2" required><br>
        <label for="email">E-Mail:</label><br>
        <input type="email" name="email" id="email" required><br>
        <input type="submit" name="register" value="Registrieren">
    </form>
    <p>Nachdem du dich registrierst, wirst du dich anmelden müssen.</p>
    <p>Hast du bereits ein Konto? <a href="./anmeldung.php">Anmelden</a></p>
    <?php
    // Connect to the database securely
    try {
        $db = new mysqli('localhost', 'root', '', 'database_name');
        if ($db->connect_error) {
            die('Verbindungsfehler: ' . $db->connect_error);
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
            if ($password !== $password2) {
                echo 'Die Passwörter stimmen nicht überein';
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
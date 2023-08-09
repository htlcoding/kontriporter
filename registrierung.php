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

    <?php
    // Hide errors
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);

    // Connect to the database
    try {
        $db = new mysqli('hostname', 'username', 'password', 'database_name');
    } catch (Exception $e) {
        echo 'Fehler beim Verbinden zur Datenbank: ' . $e->getMessage();
        exit;
    }

    // Check if a session is already started
    if (session_status() == PHP_SESSION_ACTIVE) {
        // The user is already logged in, redirect to homepage
        header('Location: /index.html');
        exit;
    }

    // Check if the register form was submitted
    if (isset($_POST['register'])) {
        // User data from the form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = $_POST['email'];

        // Check if all fields are filled
        if (empty($username) || empty($password) || empty($password2) || empty($email)) {
            echo 'Bitte alle Felder ausfüllen';
        } else {
            // Check if the passwords match
            if ($password != $password2) {
                echo 'Die Passwörter stimmen nicht überein';
            } else {
                try {
                    // Check if the username already exists
                    $stmt = $db->prepare('SELECT * FROM users WHERE username=?');
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo 'Benutzername bereits vergeben';
                    } else {
                        // Insert the user into the database
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
                        $stmt->bind_param('sss', $username, $password_hash, $email);
                        if ($stmt->execute()) {
                            echo 'Registrierung erfolgreich';
                            header('Location: /anmeldung.php');
                        } else {
                            echo 'Fehler bei der Registrierung';
                        }
                    }
                } catch (Exception $e) {
                    echo 'Ein Fehler ist aufgetreten: ' . $e->getMessage();
                }
            }
        }
    }
    ?>

    <p>Hast du bereits ein Konto? <a href="/anmeldung.php">Anmelden</a></p>
</body>
</html>
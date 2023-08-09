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
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Dieses Gerät für 30 Tage vertrauen</label><br>
        <input type="submit" name="login" value="Anmelden">
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
        header('Location: /index.php');
        exit;
    }

    // Check if the login form was submitted
    if (isset($_POST['login'])) {
        // Get the user data from the form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']); // Check if the checkbox is checked

        // Check if the username and password are correct
        try {
            $stmt = $db->prepare('SELECT * FROM users WHERE username=?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    echo 'Anmeldung erfolgreich';
                    // Check if a session is already started
                    if (session_status() != PHP_SESSION_ACTIVE) {
                        // Start a session
                        session_start();
                    }
                    // Store the username in the session variable
                    $_SESSION['username'] = $username;
                    // If the user wants to remember the device, set a cookie with the username and a random token
                    if ($remember) {
                        // Generate a random token
                        $token = bin2hex(random_bytes(16));
                        // Set the cookie for 30 days
                        setcookie('username', $username, time() + 30 * 24 * 60 * 60);
                        setcookie('token', $token, time() + 30 * 24 * 60 * 60);
                        // Store the token in the database
                        $stmt = $db->prepare('UPDATE users SET token=? WHERE username=?');
                        $stmt->bind_param('ss', $token, $username);
                        $stmt->execute();
                    }
                } else {
                    echo 'Falscher Benutzername oder Passwort';
                }
            } else {
                echo 'Falscher Benutzername oder Passwort';
            }
        } catch (Exception $e) {
            echo 'Ein Fehler ist aufgetreten: ' . $e->getMessage();
        }
    }
    ?>

    <p>Noch nicht registriert? <a href="/registrierung.php">Registrieren</a></p>
</body>
</html>
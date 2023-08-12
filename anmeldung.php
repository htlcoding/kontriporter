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
    <title>Anmeldung</title>
</head>
<body>
    <form method="post" action="">
        <label for="username">Benutzername:</label><br>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Passwort:</label><br>
        <input type="password" name="password" id="password" required><br>
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Dieses Gerät für 30 Tage vertrauen</label><br>
        <input type="submit" name="login" value="Anmelden">
    </form>
    <p>Noch nicht registriert? <a href="./registrierung.php">Registrieren</a></p>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            echo 'Bitte alle Felder ausfüllen';
        } else {
            try {
                $db = new mysqli('localhost', 'root', '', 'Database1');
                if ($db->connect_error) {
                    header('Location: servers_down.html');
                    exit;
                }

                $stmt = $db->prepare('SELECT * FROM users WHERE username=?');
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {

                        if (isset($_POST['remember'])) {
                            $token = bin2hex(random_bytes(16));

                            setcookie('username', $username, time() + 30 * 24 * 60 * 60, '/', null, false, true);
                            setcookie('token', $token, time() + 30 * 24 * 60 * 60, '/', null, false, true);

                            $stmt = $db->prepare('UPDATE users SET token=? WHERE username=?');
                            $stmt->bind_param('ss', $token, $username);
                            $stmt->execute();
                        }

                        $_SESSION['username'] = $username; // Store username in session

                        header('Location: ./index.php');
                        exit;
                    } else {
                        echo 'Falscher Benutzername oder Passwort';
                    }
                } else {
                    echo 'Falscher Benutzername oder Passwort';
                }
            } catch (Exception $e) {
                // Handle exception appropriately
                echo $e->getMessage();
                // header('Location: servers_down.html');
            }
        }
    }
    ?>
</body>
</html>
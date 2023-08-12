<?php
    function CheckLoggedIn() {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            return true;

        } elseif (isset($_COOKIE['username']) && isset($_COOKIE['token'])) {
            $username = $_COOKIE['username'];
            $token = $_COOKIE['token'];
    
            try {
                $db = new mysqli('localhost', 'root', '', 'Database1');
                if ($db->connect_error) {
                    header('Location: servers_down.html');
                    exit;
                }
    
                $stmt = $db->prepare('SELECT * FROM users WHERE username = ? AND token = ?');
                $stmt->bind_param('ss', $username, $token);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    return true;

                } else {
                    // Invalid cookies
                    // Perform logout
                    foreach ($_COOKIE as $name => $value) {
                        setcookie($name, '', time() - 3600, '/');
                    }
                    session_destroy();
                    return false;

                }
            } catch (Exception $e) {
                header('Location: servers_down.html');
                exit;
            }
        } else {
            return false;

    }
}
?>
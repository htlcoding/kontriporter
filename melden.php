<?php
require_once './scripts/user_validation.php';
session_start();
if (CheckLoggedIn()) {} else {
    header('Location: ./anmeldung.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'error') {
    echo 'Beim melden ist ein Fehler aufgetreten';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_option'])) {
        $selectedOptionIndex = $_POST['selected_option'];

        try {
            $db = new mysqli('localhost', 'root', '', 'Database1');
            if ($db->connect_error) {
                header('Location: servers_down.html');
            }
            // Check if the user has reported within the last 24 hours
            $checkReportQuery = $db->prepare('SELECT last_reported FROM users WHERE id = ? AND last_reported > DATE_SUB(NOW(), INTERVAL 1 DAY)');
            $checkReportQuery->bind_param('i', $_SESSION['user_id']);
            $checkReportQuery->execute();
            $checkReportQuery->store_result();

            if ($checkReportQuery->num_rows > 0) {
                echo 'Du hast bereits innerhalb der letzten 24 Stunden gemeldet.';
            } else {
                $stmt = $db->prepare('UPDATE kontrollliste SET reports = reports + 1 WHERE id = ?');
                $stmt->bind_param('i', $selectedOptionIndex);
                $stmt->execute();

                $stmt = $db->prepare('UPDATE ranking SET credit = credit + 5 WHERE username = ?');
                $stmt->bind_param('s', $_COOKIE['username']);
                $stmt->execute();

                // Update the user's last_reported timestamp
                $updateLastReportedQuery = $db->prepare('UPDATE users SET last_reported = NOW() WHERE id = ?');
                $updateLastReportedQuery->bind_param('i', $_SESSION['user_id']);
                $updateLastReportedQuery->execute();

                echo 'Text erfolgreich gemeldet.';
                header('Location: kontris.php?action=successreport');
            }
        } catch (Exception $e) {
            header('Location: melden.php?action=error');
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
                    header('Location: servers_down.html');
                }

                // Fetch options from the database
                $optionsQuery = $db->query('SELECT * FROM kontrollliste');
                while ($option = $optionsQuery->fetch_assoc()) {
                    echo '<option value="' . $option['id'] . '">' . htmlspecialchars($option['transport'] . ' ' . $option['line'] . ' ' . $option['station']) . '</option>';
                }
            } catch (Exception $e) {
                header('Location: servers_down.html');
            }
            ?>
        </select><br>
        <input type="submit" name="submit" value="Melden">
    </form>
</body>
</html>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U-Bahn Kontrolleure finden</title>
    <link rel="stylesheet" href="tabellen.css">
</head>
<body>
    <h2 class="Überschrift">Ranking</h2>
    <table class="list" id="rankingTable">
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Credit</th>
        </tr>
        <script>
            <?php
            $db = new mysqli('localhost', 'username', 'realactualsqlpassword', 'database_name');
            if ($db->connect_error) {
                die('Verbindungsfehler: ' . $db->connect_error);
            }

            $sql = "SELECT username, credit FROM ranking ORDER BY credit DESC";
            $result = $db->query($sql);

            if ($result) {
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                echo "var data = " . json_encode($rows) . ";";
            } else {
                echo "console.error('Error loading ranking data.');";
            }

            $db->close();
            ?>
            
            const table = document.getElementById('rankingTable');
            data.forEach((entry, index) => {
                const row = table.insertRow();
                const rankCell = row.insertCell(0);
                const nameCell = row.insertCell(1);
                const creditCell = row.insertCell(2);

                rankCell.textContent = index + 1;
                nameCell.textContent = entry.username;
                creditCell.textContent = entry.credit;
            });
        </script>
    </table>
</body>
</html>

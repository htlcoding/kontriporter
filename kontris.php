<?php
if (isset($_GET['action']) && $_GET['action'] === 'successreport') {
    echo 'Kontri erfolgreich gemeldet';
}

$db = new mysqli('localhost', 'root', '', 'Database1'); // Replace with your actual database credentials
if ($db->connect_error) {
    header('Location: servers_down.html');
}

$sql = "SELECT transport, line, station, reports FROM kontrollliste ORDER BY reports DESC"; // Order by reports in descending order
$result = $db->query($sql);

$rows = []; // Initialize an array to store fetched data
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row; // Push each fetched row into the array
    }
} else {
    echo "console.error('Error loading kontrollliste data.');";
}

$db->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrolleure finden</title>
    <link rel="stylesheet" href="tabellen.css">
</head>
<body>
    <h2 class="Überschrift">Hier findest du alle Kontrolleure in Wien</h2>
    <div class="controls">
        <label for="toggleSwitch">Liste anzeigen</label>
        <input type="checkbox" id="toggleSwitch" checked>
    </div>
    <div class="container">
        <input type="text" id="searchInput" placeholder="Suche nach Schlüsselwort">
        <div class="list" id="listContainer"></div>
    </div>
    <script>
        var data = <?php echo json_encode($rows); ?>; // Inject the PHP data into JavaScript

        var table = document.createElement("table");
        var headerRow = document.createElement("tr");
        ["Bahn", "Linie", "Endstation", "Meldungen"].forEach(headerText => {
            var headerCell = document.createElement("th");
            headerCell.textContent = headerText;
            headerRow.appendChild(headerCell);
        });
        table.appendChild(headerRow);

        data.forEach(rowData => {
            var row = document.createElement("tr");

            // Access the individual properties from the rowData object
            var cellTransport = document.createElement("td");
            cellTransport.textContent = rowData.transport;
            row.appendChild(cellTransport);

            var cellLine = document.createElement("td");
            cellLine.textContent = rowData.line;
            row.appendChild(cellLine);

            var cellStation = document.createElement("td");
            cellStation.textContent = rowData.station;
            row.appendChild(cellStation);

            var cellReports = document.createElement("td");
            cellReports.textContent = rowData.reports;
            row.appendChild(cellReports);

            table.appendChild(row);
        });

        var searchInput = document.getElementById("searchInput");
        searchInput.addEventListener("input", function () {
            var searchTerm = searchInput.value.toLowerCase();
            var rows = table.querySelectorAll("tr");
            rows.forEach((row, rowIndex) => {
                var cells = row.querySelectorAll("td");
                var matchFound = false;
                cells.forEach((cell, cellIndex) => {
                    var cellText = cell.textContent.toLowerCase();
                    if (
                        (searchTerm === "" || cellText.includes(searchTerm)) &&
                        !["bahn", "linie", "endstation", "meldungen"].includes(cellText)
                    ) {
                        matchFound = true;
                    }
                });
                row.style.display = matchFound || rowIndex === 0 ? "table-row" : "none";
            });
        });

        var toggleSwitch = document.getElementById("toggleSwitch");
        var container = document.querySelector(".container");
        toggleSwitch.addEventListener("change", function () {
            container.style.display = toggleSwitch.checked ? "block" : "none";
        });

        document.querySelector('.list').appendChild(table);
    </script>
</body>
</html>

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
        <?php
        $db = new mysqli('localhost', 'root', '', 'database_name');
        if ($db->connect_error) {
            die('Verbindungsfehler: ' . $db->connect_error);
        }

        $sql = "SELECT type, line, station, transfer FROM kontrollliste";
        $result = $db->query($sql);

        if ($result) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = array_values($row);
            }
            echo "var data = " . json_encode($rows) . ";";
        } else {
            echo "console.error('Error loading kontrollliste data.');";
        }

        $db->close();
        ?>
        
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
            rowData.forEach((cellData, cellIndex) => {
                var cell = document.createElement(cellIndex === 0 ? "th" : "td");
                if (typeof cellData === "string") {
                    var cellText = cellData.toLowerCase();
                    if (cellText === "s-bahn" || cellText === "u-bahn") {
                        var image = document.createElement("img");
                        image.src = cellText === "s-bahn"
                            ? "./ressourcen/S-Bahn.png"
                            : "./ressourcen/U-Bahn.png";
                        image.style.width = "40px";
                        image.style.height = "40px";
                        cell.appendChild(image);
                    } else {
                        cell.textContent = cellData;
                    }
                } else {
                    cell.textContent = cellData;
                }
                row.appendChild(cell);
            });
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
                        !["type", "line", "station", "transfer", "0"].includes(cellText)
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

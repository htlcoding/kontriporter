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
      <div class="list" id="listContainer">
        <script>
          fetch("./temp/liste.json").then(response => response.json()).then(data => {
            var table = document.createElement("table");
            // Create a header row
            var headerRow = document.createElement("tr");
            ["Bahn", "Linie", "Endstation", "Meldungen"].forEach(headerText => {
              var headerCell = document.createElement("th");
              headerCell.textContent = headerText;
              headerRow.appendChild(headerCell);
            });
            table.appendChild(headerRow);
            data.forEach(rowData => {
              var row = document.createElement("tr");
              rowData.forEach(cellData => {
                var cell = document.createElement("td");
                if (typeof cellData === "string") {
                  var cellText = cellData.toLowerCase();
                  if (cellText === "s-bahn" || cellText === "u-bahn") {
                    var image = document.createElement("img");
                    if (cellText === "s-bahn") {
                      image.src = "./ressourcen/S-Bahn.png"; // Check the path to the SVG image
                    } else {
                      image.src = "./ressourcen/U-Bahn.png"; // Check the path to the SVG image
                    }
                    image.style.width = "40px"; // Adjust the width as needed
                    image.style.height = "40px"; // Adjust the height as needed
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
            document.querySelector('.list').appendChild(table);
            // Search functionality
            const searchInput = document.getElementById("searchInput");
            searchInput.addEventListener("input", function() {
              const searchTerm = searchInput.value.toLowerCase();
              const rows = table.querySelectorAll("tr");
              rows.forEach((row, rowIndex) => {
                const cells = row.querySelectorAll("td");
                let matchFound = false;
                cells.forEach((cell, cellIndex) => {
                  const cellText = cell.textContent.toLowerCase();
                  if (
                    (searchTerm === "" || cellText.includes(searchTerm)) && !["type", "line", "station", "transfer", "0"].includes(cellText)) {
                    matchFound = true;
                  }
                });
                // Toggle row visibility based on match
                row.style.display = matchFound || rowIndex === 0 ? "table-row" : "none";
              });
            });
            // Toggle functionality
            const toggleSwitch = document.getElementById("toggleSwitch");
            const container = document.querySelector(".container");
            toggleSwitch.addEventListener("change", function() {
              container.style.display = toggleSwitch.checked ? "block" : "none";
            });
          }).catch(error => {
            console.error("Error fetching or parsing JSON:", error);
          });
        </script>
      </div>
    </div>
  </body>
</html>
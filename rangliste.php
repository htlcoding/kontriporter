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
</table>

<script>
    fetch('./temp/rangliste.json')
        .then(response => response.json())
        .then(data => {
            data.sort((a, b) => b.credit - a.credit); // Sort by credit in descending order
            
            const table = document.getElementById('rankingTable');
            data.forEach((entry, index) => {
                const row = table.insertRow();
                const rankCell = row.insertCell(0);
                const nameCell = row.insertCell(1);
                const creditCell = row.insertCell(2);
                
                rankCell.textContent = index + 1; // Automatically assign rank based on array index
                nameCell.textContent = entry.name;
                creditCell.textContent = entry.credit;
            });
        })
        .catch(error => console.error('Error loading ranking data:', error));
</script>

</body>
</html>
<?php
// Start the session (if not already started)
session_start();

// Simulate user login/logout actions
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Perform logout
    unset($_SESSION['user']);
    session_destroy();
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Kontri-Wien</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="paypal.php">Spenden</a></li>
                <li><a href="anmeldung.php">
                    <?php echo isset($_SESSION['user']) ? 'Abmelden' : 'Anmelden'; ?>
                </a></li>
            </ul>
        </nav>
        <div class="hero">
            <h1 id="main">Kontri</h1>
            <p id="undermain">Wien</p>
            <a href="kontris.php" class="cta-button">Kontris</a>
            <a href="melden.php" class="cta-button">Kontri melden</a>
        </div>
    </header>

    <section id="spenden">
        <div class="spenden">
            <h2>Spenden</h2>
            <p>Hilf uns, die Privatsierung zu stoppen! Ihre Spende trägt dazu bei, unsere </p>
            <p>Plattform zu verbessern und es zu ermöglichen!</p>
            <a href="paypal.php">Paypal Spende-Link</a>
            </div>
        </div>
    </section>
    <section id="ranking">
        <div class="ranking">
            <h2>Rangliste</h2>
            <p>Je mehr du meldest, desto mehr Credits bekommst du!</p>
            <p>Platzt eins des Rankings bekommt 20% der Spenden</p>
            <a href="rangliste.php">Rangliste</a>
            </div>
        </div>
    </section>
    <section id="about">
        <div class="about-us">
            <h2>Wer sind wir?</h2>
            <p>Wir sind zwei Jugendliche die sich gegen der Privatsierung von Öffis einsetzten!</p>
            <p>Wir sammeln Daten darüber wie oft und wo sich Ticket-Kontrolleure in Wien befinden!</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; Kein Unternehmen</p>
    </footer>
</body>
</html>
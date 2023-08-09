<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>PayPal</title>
    <meta http-equiv="refresh" content="5;url=https://paypal.com">
</head>
<body>
    <div id="countdown">Du wirst zu PayPal umgeleitet in 5 Sekunden</div>

    <?php
    echo "<script>";
    echo "var countdownElement = document.getElementById('countdown');";
    echo "var countdown = 5;";
    echo "var countdownInterval = setInterval(function() {";
    echo "    countdown--;";
    echo "    countdownElement.innerHTML = 'Du wirst zu PayPal umgeleitet in ' + countdown + ' Sekunden';";
    echo "    if (countdown === 0) {";
    echo "        clearInterval(countdownInterval);";
    echo "    }";
    echo "}, 1000);";
    echo "</script>";
    ?>
</body>
</html>
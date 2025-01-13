<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>MISSATGES</title>
</head>
<body id="bodyMessages">
    <header id="headerMessages">
        <h2>LOGO TEXT</h2>
        <h3>Cercar</h3>
    </header>

    <main id="mainMessages">
        <div id="matchMessages">
            <h3>Els meus matches</h3>
            <div id="matchBox">
                <?php 
                        echo "<p>Hi ha gent esperant per parlar amb tu.<br>Torna'ls el like per començar a xatejar.</p>";
                ?>
            </div>
        </div>
        <div id="message">
            <h3>Missatges</h3>
            <div id="messageBox">
                <?php
                    echo "<p>No hi ha cap conversa,<br>descobreix gent nova i fes match</p>"
                ?>
            </div>
        </div>
    </main>

    <footer id="footerMessages">
        <h3>Descobrir</h3>
        <h3>Missatges</h3>
        <h3><a href="profile.php">Perfil</a></h3>
    </footer>
</body>
</html>
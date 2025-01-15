<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <title>MISSATGES</title>
</head>

<body id="bodyMessages">
    <script>
        <?php  if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>
    <header id="headerMessages">
        <h2>LOGO TEXT</h2>
        <h3>Cercar</h3>
    </header>

    <main id="mainMessages">
        <div id="matchMessages">
            <h3>Els meus matches</h3>
            <div id="matchBox">
                <div class='match'>
                    <img src='img/aitanaBonmati.jpg' alt='Foto de perfil'>
                    <p>Aitana</p>
                </div>
            </div>
        </div>
        <div id=" message">
            <h3>Missatges</h3>
            <div id="messageBox">
                <div class='messageUser'>
                    <img src='img/aitanaBonmati.jpg' alt='Foto de perfil'>
                    <div class='messageInfo'>
                        <p class='userName'>Aitana Bonmati</p>
                        <p class='lastMessage'>Haber si te vienes pa barcelona 😘</p>
                        <p class='messageDate'>15-01-2025</p>
                    </div>
                </div>
            </div>
    </main>

    <footer id="footer">
        <h3><a href="discober.php">Descubrir</a></h3>
        <h3><a href="messages.php">Mensajes</a></h3>
        <h3><a href="profile.php">Perfil</a></h3>
    </footer>
</body>

</html>
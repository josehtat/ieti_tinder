<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?t=<?php echo time(); ?>">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
    <title>Descubrir</title>
</head>

<body id="bodyDiscober">
    <!--<script>
        <?php if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>-->
    
    <header id="headerDiscober">
        <h2>Affinity</h2>
    </header>

    <main id="mainDiscober">
        <div id="matchDiscober">
            <div id="dataProfileMacth">
                <p id="nameProfileMacth">Raul</p>
                <p id="ayeProfileMacth">34</p>
            </div>
            <div id="imgProfileMacth">
                <img src="profilePictures/rvidal2.jpg" alt="perfil">
            </div>
            <div id="optionsMatch">
                <ul>
                    <li>
                        <button id="dislikeButton" onclick="toggleImage('dislike')">
                            <img id="dislikeImg" src="img/cruzV2.png" alt="Dislike">
                        </button>
                    </li>
                    <li>
                        <button id="likeButton" onclick="toggleImage('like')">
                            <img id="likeImg" src="img/corazonV2.png" alt="Like">
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <!--<h1 id="dontProfile">NO HI HA PERFILS DISPONIBLES</h1>-->
    </main>
    
    <nav id="nav">
        <ul>
            <li>
                <h3 id="markerPage"><a href="discober.php">Descubrir</a></h3>
            </li>
            <li>
                <h3><a href="messages.php">Mensajes</a></h3>
            </li>
            <li>
                <h3><a href="profile.php">Perfil</a></h3>
            </li>
        </ul>
    </nav>
</body>

</html>

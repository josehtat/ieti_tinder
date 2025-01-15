<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/script.js"></script>
    <title>Descubrir</title>
</head>

<body id="bodyDiscober">
    <script>
        <?php if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>

    <header id="headerDiscober">
        <h2>Affinity</h2>
    </header>

    <script>
        $foundUser = [];

        function findUser() {
            var parameters = {};

            $.ajax({
                data: parameters,
                url: 'askProfiles.php',
                type: 'POST',
                success: findUserResult,
                dataType: 'json'
            });
        }

        function findUserResult(logRes) {
            console.log(logRes);
            if (logRes.status == 0) {
                $foundUser = logRes.data[0];
                console.log($foundUser);
                if ($foundUser.length == 0) {
                    $("#mainDiscober").html('<h1 id="dontProfile">NO HI HA PERFILS DISPONIBLES</h1>');
                } else {
                    $("#mainDiscober").html(`<div id="matchDiscober">
                            <div id="perfilImgMatch">
                                <img src="${$foundUser.profilePicture}" alt="perfil">
                            </div>
                            <div id="perfilInfoMatch">
                                <h2>${$foundUser.name}, ${$foundUser.age}</h2>
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
                    `);
                }
            }
        }

        findUser();
    </script>
    <?php

    ?>

    <main id="mainDiscober">
        <div id="matchDiscober">
            <div id="perfilImgMatch">
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
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
        <?php
        session_start();
        unset($_SESSION['userProfiles']);
        if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>

    <header id="headerDiscober">
        <h2>Affinity</h2>
    </header>

    <main id="mainDiscober">
        <div id="overlay"></div>
        <div id="popup">
            <p id="popup-message"></p>
            <button id="close-btn">Seguir descubriendo</button>
            <button id="redirect-btn">Ir a la conversación</button>
        </div>
        <div id="matchDiscoberNotFound">
            <h1 id="dontProfile">No hay perfiles disponibles</h1>
        </div>
        <div id="matchDiscober">
            <div id="dataProfileMatch">
                <p id="nameProfileMatch"></p>
                <p id="ageProfileMatch"></p>
            </div>
            <div id="imgProfileMatch">
                <img src="" alt="perfil">
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

    <script>
        var foundUser = [];

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function findUser(reaction) {
            var parameters = {
                reaction: reaction
            };
            console.log(parameters);

            $.ajax({
                data: parameters,
                url: 'askProfiles.php',
                type: 'POST',
                success: findUserResult,
                error: findUserResult,
                dataType: 'json'
            });
        }

        function likeFunction(reaction) {

            //Parametros tienen que ser la reaction y la id del perfil
            var reactParameters = {
                reaction: reaction,
                findUser: $("#nameProfileMatch").data('id')
            };

            console.log(reactParameters);

            $.ajax({
                data: reactParameters,
                url: 'reaction.php',
                type: 'POST',
                success: reactionResult,
                dataType: 'json'
            });
        }

        function reactionResult(reactRes) {
            console.log("ReactionResult: ");
            console.log(reactRes);
            if (reactRes.status == 0) {
                if (reactRes.match == true) {
                    const message = reactRes.data;
                    if (message) {
                        // Set the message
                        $('#popup-message').text(message);

                        // Show the popup and overlay
                        $('#popup, #overlay').fadeIn();

                        // Close button
                        $('#close-btn').click(function() {
                            $('#popup, #overlay').fadeOut();
                        });

                        // Redirect button
                        $('#redirect-btn').click(function() {
                            window.location.href = 'messages.php'; // Change to your desired URL
                        });
                    }
                }
                findUser(true);
            }
        }

        function findUserResult(logRes) {
            // console.log(logRes);
            if (logRes.status == 0) {
                if (logRes.data.length == 0) {
                    $("#dontProfile").text('No hay perfiles disponibles');
                    $("#matchDiscoberNotFound").toggle();
                    $("#matchDiscober").toggle();
                } else {
                    foundUser = logRes.data[0];
                    console.log(foundUser);
                    $("#nameProfileMatch").text(foundUser.name).data('id', foundUser.email);
                    $("#ageProfileMatch").text(foundUser.age);
                    $("#imgProfileMatch").html('<img src="' + foundUser.pictures[0] + '" alt="perfil">');
                }
            }
        }

        findUser(false);

        $("#dislikeButton").click(function() {
            likeFunction('dislike');
            setTimeout(function() {
                toggleImage('dislike');
            }, 250);

        });

        $("#likeButton").click(function() {
            likeFunction('like');
            setTimeout(function() {
                toggleImage('like');
            }, 250);
        });
    </script>
</body>

</html>
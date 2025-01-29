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
        <div id="filterOptions">
            <p>&#9776;</p>
            <div id="filterOptionsList">
                <h3>Filtrar por Preferencias</h3>
                <label for="maxDistance">Distancia máxima:</label>
                <input type="range" id="maxDistance" class="slider" min="1" max="200" value="50">
                <p id="maxDistanceValue">50 km</p>


                <br><label for="minAge">Rango de edad:</label>
                <div class="range-input">

                    <input type="number" id="minAge" min="18" max="100" value="18">
                    <input type="number" id="maxAge" min="18" max="100" value="38">
                </div>

                <div class="filter-buttons">
                    <button id="filterButton">Filtrar</button>
                    <button id="resetButton">Eliminar Filtro</button>
                </div>
            </div>
        </div>
    </header>

    <main id="mainDiscober">
        <div id="messages-container"></div>
        
        <div id="overlay"></div>
        <div id="popup">
            <p id="popup-message"></p>
            <div class="popup-buttons">
                <button id="close-btn">Seguir descubriendo</button>
                <button id="redirect-btn">Ir a la conversación</button>
            </div>
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
                        <button id="dislikeButton" onclick="toggleImage('dislike'); showMessage('info', 'Dislike')">
                            <img id="dislikeImg" src="img/cruzV2.png" alt="Dislike">
                        </button>
                    </li>
                    <li>
                        <button id="likeButton" onclick="toggleImage('like'); showMessage('info', 'Like')">
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
        var cont = 0;

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

        function logMessage(errorCode, message) {
            var text = "";
            switch (errorCode) {
                case 0:
                    text = "[INFO - discober.php] " + message;
                    break;
                case 1:
                case 2:
                case 3:
                case 4:
                    text = "[ERROR - discober.php] " + message;
                    break;
            }
            var logParameters = {
                text: text
            };

            $.ajax({
                data: logParameters,
                url: 'logs.php',
                type: 'POST',
                success: logResult,
                dataType: 'json'
            });
        }

        function logResult(logRes) {
            console.log("logResult: ");
            console.log(logRes);
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
            //console.log("ReactionResult: ");
            //console.log(reactRes);
            logMessage(reactRes.status, reactRes.data);
            if (reactRes.status == 0) {
                if (reactRes.match == true) {
                    const message = reactRes.data;
                    if (message) {
                        // Set the message
                        $('#popup-message').text("Tu y " + foundUser.name + " habeís hecho match!");

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
            console.log(logRes);
            if (logRes.status == 0) {
                if (logRes.data.length == 0) {
                    $("#dontProfile").text('No hay perfiles disponibles');
                    $("#matchDiscoberNotFound").toggle();
                    $("#matchDiscober").toggle();
                    logMessage(logRes.status, getCookie("loggedUser") + " no ha encontrado más perfiles");
                } else {
                    logMessage(logRes.status, getCookie("loggedUser") + " ha encontrado un perfil");
                    foundUser = logRes.data[0];
                    if ($("#matchDiscoberNotFound").is(":visible")) {
                        $("#matchDiscoberNotFound").toggle();
                        $("#matchDiscober").toggle();
                    }
                    //console.log(foundUser);
                    $("#nameProfileMatch").text(foundUser.name).data('id', foundUser.email);
                    $("#ageProfileMatch").text(foundUser.age);
                    $("#imgProfileMatch").html('<img src="' + foundUser.pictures[0] + '" alt="perfil">');
                }
            } else {
                logMessage(logRes.status, logRes.data);
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
        $("#filterOptions p").click(function() {
            $("#filterOptionsList").toggle();
        });

        const $minAge = $("#minAge");
        const $maxAge = $("#maxAge");

        function isNumeric(str) {
            if (typeof str != "string") return false // we only process strings!  
            return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
                !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
        }

        function updateRange() {
            if (!isNumeric($minAge.val())) {
                $minAge.val(18);
            }

            if (!isNumeric($maxAge.val())) {
                $maxAge.val(38);
            }
            const min = parseInt($minAge.val());
            const max = parseInt($maxAge.val());

            const minStr = min.toString();
            const maxStr = max.toString();

            if (minStr.length > 1) {
                if (min < 18) {
                    $minAge.val(18);
                }
                if (min > 100) {
                    $minAge.val(100);
                }
            }
            if (maxStr.length > 1) {
                if (max < 18) {
                    $maxAge.val(18);
                }
                if (max > 100) {
                    $maxAge.val(100);
                }
            }

            if (minStr.length > 1 && maxStr.length > 1) {
                // Prevent overlap
                if (min > max - 1) {
                    $minAge.val(max - 1);
                }
                if (max < min + 1) {
                    $maxAge.val(min + 1);
                }
            }
        }

        // Initialize slider
        $minAge.on("input", updateRange);
        $maxAge.on("input", updateRange);
        updateRange();

        $("#maxDistance").on("input", function() {
            $("#maxDistanceValue").text($("#maxDistance").val() + " km");
        });

        $("#filterButton").on("click", function() {
            const maxDistance = $("#maxDistance").val();
            const minAge = $("#minAge").val();
            const maxAge = $("#maxAge").val();
            var filterParameters = {
                reaction: false,
                filter: true,
                maxDistance: maxDistance,
                minAge: minAge,
                maxAge: maxAge
            };
            //console.log(filterParameters);
            $.ajax({
                data: filterParameters,
                url: 'askProfiles.php',
                type: 'POST',
                success: findUserResult,
                error: findUserResult,
                dataType: 'json'
            });
        });

        $("#resetButton").on("click", function() {
            $("#maxDistance").val(50);
            $("#maxDistanceValue").text(50);
            $("#minAge").val(18);
            $("#maxAge").val(38);
            var filterParameters = {
                reaction: false,
                filter: true,
            };

            $.ajax({
                data: filterParameters,
                url: 'askProfiles.php',
                type: 'POST',
                success: findUserResult,
                error: findUserResult,
                dataType: 'json'
            });
        });
    </script>
</body>

</html>
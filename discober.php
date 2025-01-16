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

    <main id="mainDiscober">
        <div id="matchDiscoberNotFound">
            <h1 id="dontProfile">No hay perfiles disponibles</h1>
        </div>
        <div id="matchDiscober">
            <div id="dataProfileMacth">
                <p id="nameProfileMacth">Raul</p>
                <p id="ageProfileMacth">34</p>
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

    <script>
        $foundUser = [];

        function findUser() {
            var storedUserProfiles = localStorage.getItem('userProfiles');
            if (storedUserProfiles) {
                var userProfiles = JSON.parse(storedUserProfiles);
                var logRes = { status: 0, data: userProfiles };
                // Update the UI with the stored data
                // You can loop through the userProfiles array and display the data as needed
                findUserResult(logRes);
            }

            if (!storedUserProfiles) {
                var parameters = {};

                $.ajax({
                    data: parameters,
                    url: 'askProfiles.php',
                    type: 'POST',
                    success: findUserResult,
                    dataType: 'json'
                });
            }
        }

        function likeFunction(reaction) {

            //Parametros tienen que ser la reaction y la id del perfil
            var parameters = {
                reaction: reaction,
                findUser: $("#nameProfileMacth").data('id')
            };

            console.log(parameters);

            $.ajax({
                data: parameters,
                url: 'reaction.php',
                type: 'POST',
                success: reactionResult,
                error: reactionResult,
                dataType: 'json'
            });
        }

        function reactionResult(logRes) {
            console.log(logRes);
            if (logRes.status == 0) {
                var storedUserProfiles = localStorage.getItem('userProfiles');
                var userProfiles = JSON.parse(storedUserProfiles);
                userProfiles.shift();
                localStorage.setItem('userProfiles', JSON.stringify(userProfiles));
                findUser();
            }
        }

        function findUserResult(logRes) {
            console.log(logRes);
            if (logRes.status == 0) {
                if (logRes.data.length == 0) {
                    $("#dontProfile").text('No hay perfiles disponibles');
                    $("#matchDiscoberNotFound").toggle();
                    $("#matchDiscober").toggle();
                    localStorage.removeItem("userProfiles");
                } else {
                    var foundUser = logRes.data[0];
                    console.log(foundUser);
                    $("#nameProfileMacth").text(foundUser.name).data('id', foundUser.email);
                    $("#ageProfileMacth").text(foundUser.age);
                    $("#imgProfileMacth").html('<img src="' + foundUser.pictures[0] + '" alt="perfil">');
                    // Remove the first element from the logRes.data array
                    localStorage.setItem('userProfiles', JSON.stringify(logRes.data));
                }
            }
        }

        findUser();

        $("#dislikeButton").click(function () {
            likeFunction('dislike');
            setTimeout(function () {
                toggleImage('dislike');
            }, 250);

        });

        $("#likeButton").click(function () {
            likeFunction('like');
            setTimeout(function () {
                toggleImage('like');
            }, 250);
        });
    </script>
</body>

</html>
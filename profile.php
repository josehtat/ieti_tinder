<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="js/jquery-3.7.1.min.js"></script>
    <title>Perfil</title>
</head>


<body id="bodyProfile">
    <script>
        <?php if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>
    <header id="headerProfile">
        <div id="logo">Affinity</div>
        <div id="menuButtons">
            <button id="viewButton">Mirar</button>
            <button id="editButton">Editar</button>
        </div>
        <div id="moreOptions">
            <p>...</p>
            <ul id="moreOptionsList">
                <li id="logoutProfile"><button id="logout">Cerrar sesión</p></button></li>
                <li id="editPwdProfile">Modificar la contraseña</li>
                <li id="deleteProfile">Eliminar la cuenta</li>
            </ul>
        </div>
    </header>
    <main id="mainProfile">
        <?php
        try {
            $hostname = "localhost";
            $dbname = "ieti_tinder";
            $dbUsername = "ietitinder";
            $pw = "tinder123";
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbUsername, $pw);

            $queryText = "SELECT users.name, YEAR(CURDATE()) - YEAR(users.birthday) AS age, pictures.path AS image 
                  FROM users 
                  LEFT JOIN pictures ON users.email_user = pictures.email_user 
                  WHERE users.email_user = :mail";

            $queryUser = $pdo->prepare($queryText);
            $queryUser->bindParam(':mail', $_COOKIE['loggedUser']);
            $queryUser->execute();

            $userInfo = $queryUser->fetch(PDO::FETCH_ASSOC);
            if ($userInfo) {
                $name = $userInfo['name'];
                $age = $userInfo['age'];

                $queryImages = "SELECT path FROM pictures WHERE email_user = :mail";
                $queryImgs = $pdo->prepare($queryImages);
                $queryImgs->bindParam(':mail', $_COOKIE['loggedUser']);
                $queryImgs->execute();
                $images = $queryImgs->fetchAll(PDO::FETCH_COLUMN);
            } else {
                $name = "Usuario Desconocido";
                $age = "No disponible";
                $images = ["/path/to/default/profile/image.jpg"];
            }

        } catch (PDOException $e) {
            echo "Error al acceder a la base de datos - " . $e->getMessage();
        }
        ?>

        <div id="userProfile">
            <div id="carouselContainer">
                <button id="prevImage" class="carouselArrow">&#10094;</button>
                <img src="<?php echo $images[0]; ?>" alt="Imagen de perfil" class="profileImage">
                <button id="nextImage" class="carouselArrow">&#10095;</button>
            </div>

            <div id="userInfo">
                <h2 id="userName"><?php echo $name; ?></h2>
                <span id="userAge"><?php echo $age; ?> años</span>
            </div>
        </div>
        <div id="editProfileSection" style="display: none;">
            <h3></h3>
            <form id="editForm" method="post">
                <label for="nameProfile">Nombre:</label>
                <input type="text" id="nameProfile" name="nameProfile">

                <label for="surnameProfile">Apellido:</label>
                <input type="text" id="surnameProfile" name="surnameProfile">

                <label for="aliasProfile">Alias:</label>
                <input type="text" id="aliasProfile" name="aliasProfile">


                <button type="submit" id="saveButton">Guardar</button>
            </form>
            <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                try {
                    $newName = $_POST['nameProfile'] ?? '';
                    $newSurname = $_POST['surnameProfile'] ?? '';
                    $newAlias = $_POST['aliasProfile'] ?? '';

                    $queryText = "UPDATE users SET ";
                    $params = [];

                    if ($newName !== '') {
                        $queryText .= "name = :name, ";
                        $params[':name'] = $newName;
                    }

                    if ($newSurname !== '') {
                        $queryText .= "surname = :surname, ";
                        $params[':surname'] = $newSurname;
                    }

                    if ($newAlias !== '') {
                        $queryText .= "alias = :alias, ";
                        $params[':alias'] = $newAlias;
                    }

                    $queryText = rtrim($queryText, ', ');

                    $queryText .= " WHERE email_user = :email";
                    $params[':email'] = $_COOKIE['loggedUser'];

                    $stmt = $pdo->prepare($queryText);
                    foreach ($params as $key => &$value) {
                        $stmt->bindParam($key, $value);
                    }
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        echo "Datos actualizados correctamente.";
                    } else {
                        echo "No se ha podido actualizar los datos o no se realizaron cambios.";
                    }

                } catch (PDOException $e) {
                    echo "Error al actualizar los datos: " . $e->getMessage();
                }
            }


            ?>

            <button id="editPhotosButton">Modificar les meves fotos</button>
        </div>

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
        $(document).ready(function () {
            var images = <?php echo json_encode($images); ?>;
            var cont = 0;
            var $carousel = $('#carouselContainer .profileImage');

            function changeImage() {
                $carousel.fadeOut('fast', function () {
                    $carousel.attr('src', images[cont]);
                    $carousel.fadeIn('fast');
                });
            }

            function setupEventListeners() {
                $('#nextImage').off('click').on('click', function () {
                    cont = (cont + 1) % images.length;
                    changeImage();
                });
                $('#prevImage').off('click').on('click', function () {
                    cont = (cont - 1 + images.length) % images.length;
                    changeImage();
                });
                $carousel.off('click').on('click', function () {
                    cont = (cont + 1) % images.length;
                    changeImage();
                });
                $('#editButton').off('click').on('click', function () {
                    $('#userProfile').hide();
                    $('#editProfileSection').show();
                });
                $('#viewButton').off('click').on('click', function () {
                    $('#userProfile').show();
                    $('#editProfileSection').hide();
                });
                $('#logout').off('click').on('click', function () {
                    logout();
                });
            }

            setupEventListeners();

            function logout() {
                var parameters = {};

                $.ajax({
                    data: parameters,
                    url: 'logout.php',
                    type: 'POST',
                    success: logoutResult,
                    dataType: 'json'
                });
            }

            function logoutResult(logRes) {
                console.log(logRes);
                if (logRes.status == 0) {
                    window.location.href = "/";
                }
            }

            $(document).ready(function () {
                $("#logout").click(function (event) {
                    logout();
                });
            });

            $('#editForm').on('submit', function (e) {
                e.preventDefault(); var form = $(this); $.ajax({
                    type: 'POST',
                    url: '',
                    data: form.serialize(),
                    success: function (response) {
                        console.log('Formulario enviado correctamente');
                        $('#userProfile').show(); $('#editProfileSection').hide();
                        setupEventListeners();
                    },
                    error: function (err) {
                        console.log('Error en el envío del formulario: ', err);
                    }
                });
            });
        });

    </script>
</body>

</html>
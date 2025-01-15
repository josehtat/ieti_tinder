<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <title>Perfil</title>
</head>

<body id="bodyProfile">
    <header id="headerProfile">
        <div id="logo">LOGO TEXT</div>
        <div id="menuButtons">
            <button id="viewButton">Mirar</button>
            <button id="editButton">Editar</button>
        </div>
        <div id="moreOptions">
            <p>...</p>
            <ul id="moreOptionsList">
                <li id="logoutProfile">Cerrar sesión</li>
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
                $image = $userInfo['image'];
            } else {
                $name = "Usuario Desconocido";
                $age = "No disponible";
                $image = "/path/to/default/profile/image.jpg";
            }

        } catch (PDOException $e) {
            echo "Error al acceder a la base de datos - " . $e->getMessage();
        }
        ?>
        <div id="userProfile">
            <div id="carousel">
                <img src="<?php echo $image; ?>" alt="Imagen de perfil" class="profileImage">
            </div>
            <div id="userInfo">
                <h2 id="userName"><?php echo $name; ?></h2>
                <span id="userAge"><?php echo $age; ?> años</span>
            </div>
        </div>
        <div id="editProfileSection" style="display: none;">
            <h3></h3>
            <form id="editForm">
                <label for="camp1">Camp 1:</label>
                <input type="text" id="camp1" name="camp1" required>

                <label for="camp2">Camp 2:</label>
                <input type="text" id="camp2" name="camp2" required>

                <button type="submit" id="saveButton">Guardar</button>
            </form>
            

            <button id="editPhotosButton">Modificar les meves fotos</button>
        </div>
    </main>

    <footer id="footer">
        <h3><a href="discober.php">Descubrir</a></h3>
        <h3><a href="messages.php">Mensajes</a></h3>
        <h3><a href="profile.php">Perfil</a></h3>
    </footer>

    <script>
        $(document).ready(function () {
            var images = <?php echo json_encode($images); ?>;
            var cont = 0;
            var $carousel = $('#carousel');

            function changeImage() {
                $carousel.fadeOut('fast', function () {
                    $carousel.attr('src', images[cont]);
                    $carousel.fadeIn('fast');
                });
                cont = (cont + 1) % images.length;
            }

            setInterval(changeImage, 3000);

            $carousel.on('click', function () {
                changeImage();
            });

            $('#editButton').click(function () {
                $('#userProfile').hide();
                $('#editProfileSection').show();
            });

            $('#viewButton').click(function () {
                $('#userProfile').show();
                $('#editProfileSection').hide();
            });

        });
    </script>
</body>

</html>
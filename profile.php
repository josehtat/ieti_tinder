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
      <script>
        <?php if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>
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
            <div id="carouselContainer">
                <button id="prevImage" class="carouselArrow">&#10094;</button>
                <img src="<?php echo $image; ?>" alt="Imagen de perfil" class="profileImage">
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
                $newName = $_POST['nameProfile'] ?? '';
                $newSurname = $_POST['surnameProfile'] ?? '';
                $newAlias = $_POST['aliasProfile'] ?? '';

                /*var_dump($newName);
                var_dump($newSurame);
                var_dump($newAlias);*/

                $queryText = "UPDATE users SET";
                $queryText .= "name = IF (:name != '', :name, name), ";
                $queryText .= "surname = IF (:surname != '', :surname, surname) ";
                $queryText .= "alias = IF (:alias != '', :alias, alias) ";
                $queryText .= "WHERE email_user = :email;";

                $stmt = $pdo->prepare($queryText);
                $stmt->bindParam(':name', $newName);
                $stmt->bindParam(':surname', $newSurname);
                $stmt->bindParam(':alias', $newAlias);
                $stmt->bindParam(':email', $_COOKIE['loggedUser']);
                $stmt->execute();

            }

            ?>

            <button id="editPhotosButton">Modificar les meves fotos</button>
        </div>
    
    <header id="headerProfile">
        <h2>LOGO TEXT</h2>
    </header>

    <main id="mainProfile">
        

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
            }

            $('#nextImage').click(function () {
                cont = (cont + 1) % images.length;
                changeImage();
            });

            $('#prevImage').click(function () {
                cont = (cont - 1 + images.length) % images.length;
                changeImage();
            });

            setInterval(function () {
                cont = (cont + 1) % images.length;
                changeImage();
            }, 3000);

            $carousel.on('click', function () {
                cont = (cont + 1) % images.length;
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
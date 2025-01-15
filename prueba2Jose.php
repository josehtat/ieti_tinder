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
        <div id="userProfile">
        <div id="carouselContainer">
            <button id="prevImage" class="carouselArrow">&#10094;</button>
            <img src="img/ronaldo.jpg" alt="Imagen de perfil" class="profileImage" id="carousel">
            <button id="nextImage" class="carouselArrow">&#10095;</button>
        </div>
            <div id="userInfo">
                <h2 id="userName">Cristiano</h2>
                <span id="userAge">38 años</span>
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
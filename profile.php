<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
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

            $queryText = "SELECT users.name, users.surnames, users.alias, users.sex, users.sex_orientation, YEAR(CURDATE()) - YEAR(users.birthday) AS age, pictures.path AS image, users.location
                  FROM users 

                  LEFT JOIN pictures ON users.email_user = pictures.email_user 
                  WHERE users.email_user = :mail";

            $queryUser = $pdo->prepare($queryText);
            $queryUser->bindParam(':mail', $_COOKIE['loggedUser']);
            $queryUser->execute();

            $userInfo = $queryUser->fetch(PDO::FETCH_ASSOC);
            if ($userInfo) {
                $name = $userInfo['name'];
                $surname = $userInfo['surnames'];
                $alias = $userInfo['alias'];
                $gender = $userInfo['sex'];
                $orientation = $userInfo['sex_orientation'];
                $age = $userInfo['age'];
                $location = $userInfo['location'];

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
                <input type="text" id="nameProfile" name="nameProfile" value="<?php echo $name; ?>">

                <label for="surnameProfile">Apellido:</label>
                <input type="text" id="surnameProfile" name="surnameProfile" value="<?php echo $surname; ?>">

                <label for="aliasProfile">Alias:</label>
                <input type="text" id="aliasProfile" name="aliasProfile" value="<?php echo $alias; ?>">

                <label for="genderProfile">Genero:</label>
                <select id="genderProfile" name="genderProfile">
                    <option value="M" <?php if ($gender == 'M')
                        echo 'selected'; ?>>Masculino</option>
                    <option value="F" <?php if ($gender == 'F')
                        echo 'selected'; ?>>Femenino</option>
                    <option value="NB" <?php if ($gender == 'NB')
                        echo 'selected'; ?>>No Binario</option>
                </select>

                <label for="orientationProfile">Orientación sexual</label>
                <select id="orientationProfile" name="orientationProfile">
                    <option value="heterosexual" <?php if ($orientation == 'heterosexual')
                        echo 'selected'; ?>>
                        Heterosexual</option>
                    <option value="homosexual" <?php if ($orientation == 'homosexual')
                        echo 'selected'; ?>>Homosexual
                    </option>
                    <option value="bisexual" <?php if ($orientation == 'bisexual')
                        echo 'selected'; ?>>Bisexual</option>
                </select>
                </select>

                <div class="input-row" id="locationInput">
                    <div class="input-group">
                        <label for="latitude">Latitud</label>
                        <input type="text" id="latitude" name="latitude" required readonly>
                    </div>

                    <div class="input-group">
                        <label for="longitude">Longitud</label>
                        <input type="text" id="longitude" name="longitude" required readonly>
                    </div>
                </div>

                <div id="map" style="height: 300px; width: 100%;"></div>

                <button type="submit" id="saveButton">Guardar</button>
            </form>
            <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                try {
                    $newName = $_POST['nameProfile'] ?? '';
                    $newSurname = $_POST['surnameProfile'] ?? '';
                    $newAlias = $_POST['aliasProfile'] ?? '';
                    $newGender = $_POST['genderProfile'] ?? '';
                    $newOrientation = $_POST['orientationProfile'] ?? '';

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

                    if ($newGender !== '') {
                        $queryText .= "sex = :gender, ";
                        $params[':gender'] = $newGender;
                    }

                    if ($newOrientation !== '') {
                        $queryText .= "sex_orientation = :orientation, ";
                        $params[':orientation'] = $newOrientation;
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
                <h3><a href="discober.php">Descubrir</a></h3>
            </li>
            <li>
                <h3><a href="messages.php">Mensajes</a></h3>
            </li>
            <li>
                <h3 id="markerPage"><a href="profile.php">Perfil</a></h3>
            </li>
        </ul>
    </nav>

    <script>
        // Crear un ícono personalizado
        const customIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // URL de la imagen del ícono
            iconSize: [30, 30], // Tamaño del ícono [ancho, alto]
            iconAnchor: [15, 30], // Punto de anclaje [x, y]
            popupAnchor: [0, -30] // Punto donde aparece el popup [x, y]
        });

        // Configurar el mapa
        const map = L.map('map').setView([0, 0], 2); // Vista inicial del mapa

        // Agregar un mapa base (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Crear marcador vacío
        let marker;

        // Detectar clics en el mapa
        map.on('click', function (e) {
            const lat = e.latlng.lat.toFixed(6); // Redondear latitud
            const lng = e.latlng.lng.toFixed(6); // Redondear longitud

            // Mostrar las coordenadas en los campos de entrada
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Mover o crear el marcador con el ícono personalizado
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng, { icon: customIcon }).addTo(map);
            }

        });



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
                e.preventDefault(); var form = $(this);
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: form.serialize(),
                    success: function (response) {
                        console.log('Formulario enviado correctamente');
                        $('#userProfile').show();
                        $('#editProfileSection').hide();
                        setupEventListeners();
                        window.location.href = "/profile.php";
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
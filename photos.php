<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <title>Fotos</title>
</head>

<body id="bodyPhotos">
    <script>
        <?php if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>
    <header id="headerProfile">
        <div id="logo">Affinity</div>
    </header>

    <main id="mainPhotos">
        <h2>Les meves fotos</h2>
        <div id="photoGrid">
            <?php
            $hostname = "localhost";
            $dbname = "ieti_tinder";
            $dbUsername = "ietitinder";
            $pw = "tinder123";
            try {
                $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbUsername, $pw);

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['newPhoto']) && $_FILES['newPhoto']['error'] == UPLOAD_ERR_OK) {
                    $uploadDir = 'profilePictures/';
                    $file_name = basename($_FILES['newPhoto']['name']);
                    $uploadFile = $uploadDir . $file_name;
                    if (move_uploaded_file($_FILES['newPhoto']['tmp_name'], $uploadFile)) {
                        $query = "INSERT INTO pictures (email_user, path) VALUES (:email, :path)";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':email', $_COOKIE['loggedUser']);
                        $stmt->bindParam(':path', $uploadFile);
                        if ($stmt->execute()) {
                            echo "Imagen subida y registrada correctamente.";
                        } else {
                            echo "Error en la ejecución de la consulta SQL.";
                        }
                    } else {
                        echo "Error al mover la foto a su destino final.";
                    }
                } else {
                    echo "";
                }
                $query = "SELECT path FROM pictures WHERE email_user = :email";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':email', $_COOKIE['loggedUser']);
                $stmt->execute();
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($images as $image) {
                    echo '<div class="photoBox"><img src="' . htmlspecialchars($image['path']) . '" alt="Foto"><button class="deletePhoto">X</button></div>';
                }
            } catch (PDOException $e) {
                echo "Error al acceder a la base de datos: " . $e->getMessage();
            }
            ?>
            <div class="photoBox addPhoto">
                <form id="uploadPhotoForm" method="post" enctype="multipart/form-data">
                    <input type="file" id="newPhoto" name="newPhoto" accept="image/*" style="display: none;">
                    <label for="newPhoto" class="addPhotoBtn">+</label>
                </form>
            </div>
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
                <h3><a href="profile.php">Perfil</a></h3>
            </li>
        </ul>
    </nav>
    <script>
        $(document).ready(function () {
            const photoGrid = $('#photoGrid');

            $('#newPhoto').on('change', function () {
                $('#uploadPhotoForm').submit();
            });

            $('.addPhotoBtn').click(function () {
                $('#newPhoto').click();
            });

            function setupDeleteListeners() {
                $('.deletePhoto').off('click').on('click', function () {
                    $(this).parent().remove();
                });
            }

            setupDeleteListeners();
        });


    </script>
</body>

</html>
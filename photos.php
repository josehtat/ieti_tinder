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
            $maxImages = 6;

            $hostname = "localhost";
            $dbname = "ieti_tinder";
            $dbUsername = "ietitinder";
            $pw = "tinder123";
            try {
                $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbUsername, $pw);

                // Verificar el número de imágenes antes de permitir una nueva subida
                $countQuery = "SELECT count(*) as total FROM pictures WHERE email_user = :email";
                $countStmt = $pdo->prepare($countQuery);
                $countStmt->bindParam(':email', $_COOKIE['loggedUser']);
                $countStmt->execute();
                $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
                $totalImages = $countResult['total'];

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletePhoto'])) {
                    if ($totalImages > 1) {
                        $imageId = $_POST['deletePhoto'];
                        $query = "DELETE FROM pictures WHERE id = :id";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':id', $imageId);
                        if ($stmt->execute()) {
                            echo "";
                        } else {
                            echo "Error al eliminar la imagen.";
                        }
                    }
                }

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['newPhoto']) && $_FILES['newPhoto']['error'] == UPLOAD_ERR_OK) {
                    // Si no se ha alcanzado el límite de imágenes, subir la nueva foto
                    if ($totalImages < $maxImages) {
                        $uploadDir = 'profilePictures/';
                        $file_name = basename($_FILES['newPhoto']['name']);
                        $uploadFile = $uploadDir . $file_name;
                        if (move_uploaded_file($_FILES['newPhoto']['tmp_name'], $uploadFile)) {
                            $query = "INSERT INTO pictures (email_user, path) VALUES (:email, :path)";
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':email', $_COOKIE['loggedUser']);
                            $stmt->bindParam(':path', $uploadFile);
                            if ($stmt->execute()) {
                                echo "";
                            } else {
                                echo "Error en la ejecución de la consulta SQL.";
                            }
                        } else {
                            echo "Error al mover la foto a su destino final.";
                        }
                    } else {
                        echo "";
                    }
                }

                // Mostrar las imágenes
                $query = "SELECT id, path FROM pictures WHERE email_user = :email";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':email', $_COOKIE['loggedUser']);
                $stmt->execute();
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($images as $image) {
                    echo '<div class="photoBox">
                            <img src="' . htmlspecialchars($image['path']) . '" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            <form method="post" style="z-index: 5"> 
                                <input type="hidden" name="deletePhoto" value="' . $image['id'] . '"> 
                                <button type="submit" class="deletePhotoBtn">X</button> 
                            </form>
                        </div>';
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
            function toggleAddPhotoButton() {
                const totalImages = <?php echo $totalImages; ?>;
                const maxImages = <?php echo $maxImages; ?>;
                if (totalImages == maxImages) {
                    $('.addPhoto').hide();
                } else {
                    $('.addPhoto').show();
                }
            }

            toggleAddPhotoButton();

            $('#newPhoto').on('change', function () {
                $('#uploadPhotoForm').submit();
            });

            $('.addPhotoBtn').click(function () {
                $('#newPhoto').click();
            });
        });


    </script>
</body>

</html>
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
        <h2>Mis Fotos</h2>
        <div id="photoGrid">
            <?php
            $maxImages = 6;

            $hostname = "localhost";
            $dbname = "ieti_tinder";
            $dbUsername = "ietitinder";
            $pw = "tinder123";
            try {
                $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbUsername, $pw);
                $query = "SELECT id, path FROM pictures WHERE email_user = :email";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':email', $_COOKIE['loggedUser']);
                $stmt->execute();
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $totalImages = count($images);

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
            function toggleAddPhotoButton(totalImages) {
                const maxImages = <?php echo $maxImages; ?>;
                if (totalImages >= maxImages) {
                    $('.addPhoto').hide();
                } else {
                    $('.addPhoto').show();
                }
            }

            toggleAddPhotoButton(<?php echo $totalImages; ?>);

            $('#newPhoto').on('change', function () {
                var formData = new FormData($('#uploadPhotoForm')[0]);
                $.ajax({
                    url: 'handle_photos.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $('#photoGrid .addPhoto').before('<div class="photoBox"><img src="' + response.imagePath + '" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;"><form method="post" style="z-index: 5"><input type="hidden" name="deletePhoto" value="' + response.imageId + '"><button type="submit" class="deletePhotoBtn">X</button></form></div>');
                            toggleAddPhotoButton(response.totalImages);
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            $(document).on('click', '.deletePhotoBtn', function (e) {
                e.preventDefault();
                var form = $(this).closest('form');
                $.ajax({
                    url: 'handle_photos.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        if (response.success) {
                            form.closest('.photoBox').remove();
                            toggleAddPhotoButton(response.totalImages);
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

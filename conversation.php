<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    <title>Conversación</title>
</head>


<body id="bodyProfile">
    <script>
        <?php if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>
    <header id="headerProfile">
        <?php
        try {
            $hostname = "localhost";
            $dbname = "ieti_tinder";
            $dbUsername = "ietitinder";
            $pw = "tinder123";
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
        } catch (PDOException $e) {
            echo "Error al accedir a la base de dades - " . $e->getMessage() . "\n";
            exit;
        }

        $mail_receptor = $_GET['mail'];

        $query = "SELECT p.path
        FROM pictures p
        JOIN users u ON p.email_user = u.email_user
        WHERE u.email_user = :mail_receptor";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':mail_receptor', $mail_receptor);
        $stmt->execute();
        $row = $stmt->fetch();
        $image_path = $row['path'];
        ?>
        <div id="logo">
            <?php echo "<img src='profilePictures/'$image_path[0].jpg alt ='Foto de perfil'>" ?>
        </div>
        <div id="menuButtons">
            <button id="viewButton">Conversación</button>
            <button id="editButton">Perfil</button>
        </div>
    </header>
    <main id="mainProfile">
        <div id="userProfile">

        </div>
        <div id="editProfileSection" style="display: none;">
            <div id="carouselContainer">
                <button id="prevImage" class="carouselArrow">&#10094;</button>
                <img src="profilePictures/rvidal1.jpg" alt="Imagen de perfil" class="profileImage">
                <button id="nextImage" class="carouselArrow">&#10095;</button>
            </div>

            <div id="userInfo">
                <h2 id="userName"></h2>
                <span id="userAge"></span>
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
                <h3 id="markerPage"><a href="profile.php">Perfil</a></h3>
            </li>
        </ul>
    </nav>

    <script>
        $(document).ready(function () {
            var $carousel = $('#carouselContainer .profileImage');
            $('#editButton').off('click').on('click', function () {
                console.log("Botón de editar clicado");
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
        });

    </script>
</body>

</html>
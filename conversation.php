<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="/js/script.js"></script>
    <title>Conversación</title>
</head>

<body id="bodyConversation">
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
            echo "Error al acceder a la base de datos - " . $e->getMessage() . "\n";
            exit;
        }

        $mail_receptor = $_GET['mail'];

        // Obtener imágenes del perfil del receptor
        $query = "SELECT p.path FROM pictures p JOIN users u ON p.email_user = u.email_user WHERE u.email_user = :mail_receptor";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':mail_receptor', $mail_receptor);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Obtener nombre y edad del receptor
        $query = "SELECT u.name, TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) AS age FROM users u WHERE u.email_user = :mail_receptor";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':mail_receptor', $mail_receptor);
        $stmt->execute();
        $user_info = $stmt->fetch();
        $name = $user_info['name'];
        $age = $user_info['age'];
        ?>

        <div id="backArrow">
            <a href="messages.php" class="arrowLink" style="text-decoration: none;">
                <span class="arrowLeft">&#x2190;</span>
            </a>
        </div>
        <div id="logo">
            <?php echo "<img src='{$images[0]}' alt='Foto de perfil'>" ?>
        </div>
        <div id="menuTabs">
            <button class="tablink" id="viewTab">Conversación</button>
            <button class="tablink" id="editTab">Perfil</button>
        </div>
    </header>
    <main id="mainConversation">
        <div id="messages-container"></div>
        <div id="userConversation">
            <div class="conversation" id="messagesContainer"></div>
            <div id="sendMessage">
                <form id="sendMessageForm" method="POST">
                    <textarea id="messageTextArea" name="message" placeholder="Mensaje" required></textarea>
                    <input type="hidden" name="receiver_email" value="<?php echo $mail_receptor; ?>">
                    <button type="submit" name="send_message">Enviar</button>
                </form>
            </div>
        </div>

        <div id="editProfileSection" style="display: none;">
            <div id="userProfile">
                <div id="carouselContainer">
                    <img src="<?php echo $images[0]; ?>" alt="Imagen de perfil" class="profileImage">
                    <div id="carouselDots">
                        <?php for ($i = 0; $i < count($images); $i++) { ?>
                            <span class="carouselDot <?php if ($i == 0) {
                                echo 'active';
                            } ?>"></span>
                        <?php } ?>
                    </div>
                </div>

                <div id="userInfo">
                    <h2 id="userName"><?php echo $name; ?></h2>
                    <span id="userAge"><?php echo $age; ?> años</span>
                </div>
            </div>
        </div>
    </main>

    <nav id="nav">
        <ul>
            <li>
                <h3><a href="discober.php">Descubrir</a></h3>
            </li>
            <li>
                <h3 id="markerPage"><a href="messages.php">Mensajes</a></h3>
            </li>
            <li>
                <h3><a href="profile.php">Perfil</a></h3>
            </li>
        </ul>
    </nav>
</body>

</html>

<script>
    $(document).ready(function () {
        $(document).on('click', '.heartButton', function () {
            var messageId = $(this).data('message-id');
            $.ajax({
                url: 'fetch_messages.php',
                type: 'POST',
                data: { message_id: messageId, user_email: '<?php echo $_COOKIE['loggedUser']; ?>' },
                success: function (response) {
                    if (response.success) {
                        var heartImage = $('#heartImage_' + messageId);
                        heartImage.attr('src', response.newStatus ? 'img/corazonV1.png' : 'img/corazonV2.png');
                    } else {
                        console.log(response.message); // Mostrar mensaje de error en la consola
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error al dar like: ' + error); // Registrar cualquier otro error en la consola
                }
            });
        });

        // Llamar a fetchMessages al cargar la página
        fetchMessages();

        // Tabs functionality
        $('#viewTab').click(function () {
            $('#userConversation').show();
            $('#editProfileSection').hide();
            $('#viewTab').addClass('active');
            $('#editTab').removeClass('active');
        });

        $('#editTab').click(function () {
            $('#userConversation').hide();
            $('#editProfileSection').show();
            $('#editTab').addClass('active');
            $('#viewTab').removeClass('active');
        });

        $('#viewTab').click(); // Set default tab

        // Enviar mensaje con AJAX
        $('#sendMessageForm').submit(function (event) {
            event.preventDefault(); // Evitar recarga de la página

            var formData = $(this).serialize();
            $.ajax({
                url: 'fetch_messages.php',
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        fetchMessages(); // Refrescar mensajes después de enviar
                        $('#messageTextArea').val(''); // Limpiar el textarea
                        $('#sendMessageForm').trigger("reset"); // Otra manera de limpiar el textarea
                    } else {
                        console.log(response.message); // Mostrar mensaje de error en la consola
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error al enviar mensaje: ' + error);
                }
            });
        });

        // Fetch messages every 1 second
        setInterval(fetchMessages, 1000);

        // Carrusel de imágenes
        var images = <?php echo json_encode($images); ?>;
        var cont = 0;
        var $carousel = $('#carouselContainer .profileImage');

        function changeImage() {
            $carousel.fadeOut('fast', function () {
                $carousel.attr('src', images[cont]);
                $carousel.fadeIn('fast');
            });
        }

        $carousel.off('click').on('click', function () {
            cont = (cont + 1) % images.length;
            changeImage();
            $('.carouselDot').removeClass('active');
            $('.carouselDot').eq(cont).addClass('active');
        });
    });

    function fetchMessages() {
        $.ajax({
            url: 'fetch_messages.php',
            type: 'GET',
            data: { mail: '<?php echo $mail_receptor; ?>' },
            success: function (data) {
                $('#messagesContainer').html(data);
            },
            error: function (xhr, status, error) {
                console.log('Error al obtener mensajes: ' + error);
            }
        });

    }
</script>
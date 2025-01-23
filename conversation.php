<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="js/jquery-3.7.1.min.js"></script>
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

        <div id="backArrow">
            <a href="messages.php" class="arrowLink" style="text-decoration: none;">
                <span class="arrowLeft">&#x2190;</span>
            </a>
        </div>
        <div id="logo">
            <?php echo "<img src='$image_path' alt='Foto de perfil'>" ?>
        </div>
        <div id="menuTabs">
            <button class="tablink" id="viewTab">Conversación</button>
            <button class="tablink" id="editTab">Perfil</button>
        </div>
    </header>
    <main id="mainConversation">
        <div id="userConversation">
            <div class="conversation">
                <?php
                // Obtener los mensajes entre el usuario logueado y el receptor
                $query = "
                    SELECT id_user, message_user, date 
                    FROM messages
                    WHERE (id_user = :user_email AND id_receptor = :receiver_email)
                    OR (id_user = :receiver_email AND id_receptor = :user_email)
                    ORDER BY date ASC
                ";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':user_email', $_COOKIE['loggedUser']);
                $stmt->bindParam(':receiver_email', $mail_receptor);
                $stmt->execute();
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Mostrar los mensajes
                $last_time = null;
                foreach ($messages as $message):
                    $sender = ($message['id_user'] == $_COOKIE['loggedUser']) ? 'sent' : 'received';
                    $message_time = strtotime($message['date']);
                    $current_time = time();
                    $time_diff = $current_time - $message_time;

                    // Mostrar la fecha solo si ha pasado más de 5 minutos desde el último mensaje
                    $show_date = false;

                    // Si es el primer mensaje o han pasado más de 5 minutos desde el último mensaje, mostramos la fecha
                    if (!$last_time || $time_diff > 300) {
                        $show_date = true;
                    }

                    // Mostrar la fecha solo si se debe mostrar
                    if ($show_date) {
                        $formatted_date = date("d M Y, H:i", $message_time);
                        echo "<div class='date'>$formatted_date</div>";
                    }

                    if ($sender == 'received') {
                        echo "<div class='messageWithImage'>
                                <img src='$image_path' alt='Foto de perfil' class='profileImageConversation'>
                                <div class='messageConversation $sender'>" . htmlspecialchars($message['message_user']) . "</div>
                              </div>";
                    } else {
                        echo "<div class='messageConversation $sender'>" . htmlspecialchars($message['message_user']) . "</div>";
                    }

                    $last_time = $message_time;
                endforeach;
                ?>
            </div>
        </div>

        <!-- Formulario para enviar mensajes -->
        <div id="sendMessage">
            <form method="POST" action="conversation.php?mail=<?php echo $mail_receptor; ?>">
                <textarea name="message" placeholder="Mensaje" required></textarea>
                <input type="hidden" name="receiver_email" value="<?php echo $mail_receptor; ?>">
                <button type="submit" name="send_message">Enviar</button>
            </form>
        </div>

        <?php
        // Enviar mensaje al hacer submit
        if (isset($_POST['send_message'])) {
            try {
                // Obtener el mensaje y el email del usuario logueado
                $message = $_POST['message'];
                $user_email = $_COOKIE['loggedUser'];
                $receiver_email = $_POST['receiver_email'];
                $date = date('Y-m-d H:i:s');

                // Insertar el mensaje en la base de datos
                $query = "INSERT INTO messages (id_user, id_receptor, message_user, date) 
                          VALUES (:user_email, :receiver_email, :message, :date)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':user_email', $user_email);
                $stmt->bindParam(':receiver_email', $receiver_email);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':date', $date);

                if ($stmt->execute()) {
                    // Redirigir para recargar la página y mostrar el nuevo mensaje
                    echo "<script>window.location.href='conversation.php?mail=$receiver_email';</script>";
                    exit;
                } else {
                    echo "Hubo un problema al enviar el mensaje.";
                }
            } catch (PDOException $e) {
                echo "Error al acceder a la base de datos: " . $e->getMessage();
            }
        }
        ?>
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

    <script>
        
        $(document).ready(function () {
            var $carousel = $('#carouselContainer .profileImage');
            $('#viewTab').click(function () {
                $('#userProfile').show();
                $('#editProfileSection').hide();
                $('#viewTab').addClass('active');
                $('#editTab').removeClass('active');
            });
            $('#editTab').click(function () {
                $('#userProfile').hide();
                $('#editProfileSection').show();
                $('#editTab').addClass('active');
                $('#viewTab').removeClass('active');
            }); // Asegurarse de que 'Mirar' esté activo al cargar la página 
            $('#viewTab').click();
            $('#logout').off('click').on('click', function () {
                logout();
            });
        });

    </script>
</body>

</html>
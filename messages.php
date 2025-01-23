<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <title>Mensajes</title>
</head>


<body id="bodyMessages">
    <script>
        <?php if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php } ?>
    </script>
    <header id="headerMessages">
        <h2>Affinity</h2>
        <h3>Buscar</h3>
    </header>

    <main id="mainMessages">
        <div id="matchMessages">
            <h3>Mis matches</h3>
            <div id="matchBox">
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

                $queryText = "SELECT * FROM interactions WHERE (id_user = :mail OR id_receptor = :mail) AND like_user = 2 AND like_receptor = 2;";

                try {
                    $queryInteractions = $pdo->prepare($queryText);
                    $queryInteractions->bindParam(':mail', $_COOKIE['loggedUser']);
                    $queryInteractions->execute();
                } catch (PDOException $e) {
                    echo "Error de SQL<br>\n";
                    $e = $queryInteractions->errorInfo();
                    if ($e[0] != '00000') {
                        echo "\nPDO::errorInfo():\n";
                        die("Error accedint a dades: " . $e[2]);
                    }
                }

                if ($queryInteractions->rowCount() > 0) {
                    foreach ($queryInteractions as $row) {
                        // Identificar el usuario del match
                        $matchedUser = ($row['id_user'] == $_COOKIE['loggedUser']) ? $row['id_receptor'] : $row['id_user'];

                        // Consulta para obtener datos del usuario
                        $queryText = "SELECT * FROM users WHERE email_user = :mail;";
                        try {
                            $queryUser = $pdo->prepare($queryText);
                            $queryUser->bindParam(':mail', $matchedUser);
                            $queryUser->execute();
                        } catch (PDOException $e) {
                            echo "Error de SQL<br>\n";
                            $e = $queryUser->errorInfo();
                            if ($e[0] != '00000') {
                                echo "\nPDO::errorInfo():\n";
                                die("Error accedint a dades: " . $e[2]);
                            }
                        }

                        // Obtener la primera imagen del usuario
                        $queryImageText = "SELECT path FROM pictures WHERE email_user = :user_id LIMIT 1;";
                        try {
                            $queryImage = $pdo->prepare($queryImageText);
                            $queryImage->bindParam(':user_id', $matchedUser);
                            $queryImage->execute();
                            $imageRow = $queryImage->fetch(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            echo "Error de SQL<br>\n";
                            $e = $queryImage->errorInfo();
                            if ($e[0] != '00000') {
                                echo "\nPDO::errorInfo():\n";
                                die("Error accedint a dades: " . $e[2]);
                            }
                        }

                        // Obtener información del usuario y renderizar
                        foreach ($queryUser as $rowUser) {
                            echo "<div class='match'>
                                    <img src=''.jpg'>
                                    <p>" . $rowUser['name'] . "</p>
                                    <p class='matchMail'>" . $rowUser['email_user'] . "</p>
                                </div>";
                        }
                    }
                } else {
                    echo "<p>Hay gente esperando a hablar contigo.<br> Devuelve los likes para comenzar a hablar.</p>\n";
                }

                $numMatches = 0;
                ?>
            </div>
        </div>
        <div id="message">
            <h3>Mensajes</h3>
            <div id="messageBox">
                <?php
                $query = "SELECT * FROM messages
                WHERE id_user = :mail OR id_receptor = :mail
                ORDER BY date DESC";

                try {
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':mail', $_COOKIE['loggedUser']);
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo "Error en la consulta SQL: " . $e->getMessage();
                    exit;
                }

                // Verificar si hay mensajes
                if ($stmt->rowCount() > 0) {
                    $lastMessages = [];

                    // Recorrer los mensajes y agruparlos por usuario
                    foreach ($stmt as $row) {
                        $userEmail = ($row['id_user'] == $_COOKIE['loggedUser']) ? $row['id_receptor'] : $row['id_user'];

                        // Si aún no existe un grupo para este usuario, crearlo
                        if (!isset($lastMessages[$userEmail])) {
                            $lastMessages[$userEmail] = [
                                'userEmail' => $userEmail,
                                'lastMessage' => $row['message_user'],
                                'date' => $row['date']
                            ];
                        } else {
                            // Actualizar el último mensaje y fecha
                            if (strtotime($row['date']) > strtotime($lastMessages[$userEmail]['date'])) {
                                $lastMessages[$userEmail]['lastMessage'] = $row['message_user'];
                                $lastMessages[$userEmail]['date'] = $row['date'];
                            }
                        }
                    }

                    // Mostrar las conversaciones
                    foreach ($lastMessages as $userData) {
                        // Recuperar información del usuario
                        $queryUser = "SELECT * FROM users WHERE email_user = :email";
                        $stmtUser = $pdo->prepare($queryUser);
                        $stmtUser->bindParam(':email', $userData['userEmail']);
                        $stmtUser->execute();
                        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

                        // Obtener la imagen del usuario
                        $queryImageText = "SELECT path FROM pictures WHERE email_user = :user_id LIMIT 1;";
                        try {
                            $queryImage = $pdo->prepare($queryImageText);
                            $queryImage->bindParam(':user_id', $userData['userEmail']);
                            $queryImage->execute();
                            $imageRow = $queryImage->fetch(PDO::FETCH_ASSOC);
                            $messageImagePath = $imageRow && !empty($imageRow['path']) ? htmlspecialchars($imageRow['path']) : 'default.jpg';
                        } catch (PDOException $e) {
                            echo "Error de SQL<br>\n";
                            $e = $queryImage->errorInfo();
                            if ($e[0] != '00000') {
                                echo "\nPDO::errorInfo():\n";
                                die("Error accedint a dades: " . $e[2]);
                            }
                        }

                        echo "<div class='messageUser'>
                                <img src='" . $messageImagePath . "' alt='Foto de perfil'>
                                <div class='messageInfo' onclick='window.location.href = \"conversation.php?mail=" . htmlspecialchars($userData['userEmail']) . "\"'>
                                    <p class='userName'>" . htmlspecialchars($user['name']) . "</p>
                                    <p class='lastMessage'>" . htmlspecialchars($userData['lastMessage']) . "</p>
                                    <p class='messageDate'>" . htmlspecialchars($userData['date']) . "</p>
                                </div>
                              </div>";
                    }
                } else {
                    echo "<p>No hay mensajes disponibles.<br> Empieza una conversación ahora.</p>";
                }

                ?>
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
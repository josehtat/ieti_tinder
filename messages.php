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
                        $queryText = "SELECT * FROM users WHERE email_user = :mail;";

                        try {
                            $queryUser = $pdo->prepare($queryText);
                            $queryUser->bindParam(':mail', $row['id_receptor']);
                            $queryUser->execute();
                        } catch (PDOException $e) {
                            echo "Error de SQL<br>\n";
                            $e = $queryUser->errorInfo();
                            if ($e[0] != '00000') {
                                echo "\nPDO::errorInfo():\n";
                                die("Error accedint a dades: " . $e[2]);
                            }
                        }

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
                WHERE (id_user = :mail OR id_receptor = :mail)
                /*AND (id_user = :findUser OR id_receptor = :findUser)*/
                ORDER BY date DESC
                LIMIT 1";

                try {
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':mail', $_COOKIE['loggedUser']);
                    /*$stmt->bindParam(':findUser', $foundUser);*/
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo "Error en la consulta SQL: " . $e->getMessage();
                    exit;
                }

                // Renderizado de mensajes
                if ($stmt->rowCount() > 0) {
                    foreach ($stmt as $row) {
                        echo "<div class='messageUser'>
                            <img src='profilePictures/egil1.jpg' alt='Foto de perfil'>
                                    <div class='messageInfo' onclick='window.location.href = \"conversation.php?mail=" . htmlspecialchars($row['id_user']) . "\"'>
                                <p class='userName'>" . htmlspecialchars($row['id_user']) . "</p>
                                <p class='lastMessage'>" . htmlspecialchars($row['message_user']) . "</p>
                                <p class='messageDate'>" . htmlspecialchars($row['date']) . "</p>
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
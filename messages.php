<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <title>MISSATGES</title>
</head>

<body id="bodyMessages">
    <!---<script>
        <?php // if (!isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/";
        <?php //} ?>
    </script>---->
    <header id="headerMessages">
        <h2>LOGO TEXT</h2>
        <h3>Cercar</h3>
    </header>

    <main id="mainMessages">
        <div id="matchMessages">
            <h3>Els meus matches</h3>
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

                $queryText1 = "SELECT * FROM interactions WHERE id_user = :mail AND like_user = true AND like_receptor = true;";

                try {
                    $queryText1 = $pdo->prepare($queryText1);
                    $queryText1->bindParam(':mail', $_COOKIE['loggedUser']);
                    $queryText1->execute();
                } catch (PDOException $e) {
                    echo "Error de SQL<br>\n";
                    $e = $queryText1->errorInfo();
                    if ($e[0] != '00000') {
                        echo "\nPDO::errorInfo():\n";
                        die("Error accedint a dades: " . $e[2]);
                    }
                }

                if ($queryText1->rowCount() <= 0) {
                    $queryText2 = "SELECT * FROM interactions WHERE id_receptor = :mail AND like_user = true AND like_receptor = true;";

                    try {
                        $queryText2 = $pdo->prepare($queryText2);
                        $queryText2->bindParam(':mail', $_COOKIE['loggedUser']);
                        $queryText2->execute();
                    } catch (PDOException $e) {
                        echo "Error de SQL<br>\n";
                        $e = $queryText2->errorInfo();
                        if ($e[0] != '00000') {
                            echo "\nPDO::errorInfo():\n";
                            die("Error accedint a dades: " . $e[2]);
                        }
                    }

                    if ($queryText2->rowCount() <= 0) {
                        echo "<p>Hay gente esperando para hablar contigo.<br> Devuelveles el like para comenzar a xatejar.</p>";
                    } else {
                        foreach ($queryText2 as $row) {
                            $queryUser = "SELECT * FROM users WHERE email_user = :mail;";

                            try {
                                $queryUser = $pdo->prepare($queryUser);
                                $queryUser->bindParam(':mail', $row['id_user']);
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
                                        <img src='img/" . $rowUser['email_user'] . ".jpg'>
                                        <p>" . $rowUser['name'] . "</p>
                                    </div>";
                            }
                        }
                    }

                } else {
                    foreach ($queryText1 as $row) {
                        $queryUser = "SELECT * FROM users WHERE email_user = :mail;";

                        try {
                            $queryUser = $pdo->prepare($queryUser);
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
                                    <img src='img/" . $rowUser['email_user'] . ".jpg'>
                                    <p>" . $rowUser['name'] . "</p>
                                </div>";
                        }
                    }
                }

                $numMatches = 0;
                ?>

            </div>
        </div>
        <div id="message">
            <h3>Missatges</h3>
            <div id="messageBox">
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

                $queryText1 = "SELECT * FROM interactions WHERE id_user = :mail AND like_user = true AND like_receptor = true;";

                try {
                    $queryText1 = $pdo->prepare($queryText1);
                    $queryText1->bindParam(':mail', $_COOKIE['loggedUser']);
                    $queryText1->execute();
                } catch (PDOException $e) {
                    echo "Error de SQL<br>\n";
                    $e = $queryText1->errorInfo();
                    if ($e[0] != '00000') {
                        echo "\nPDO::errorInfo():\n";
                        die("Error accedint a dades: " . $e[2]);
                    }
                }

                if ($queryText1->rowCount() <= 0) {
                    $queryText2 = "SELECT * FROM interactions WHERE id_receptor = :mail AND like_user = true AND like_receptor = true;";

                    try {
                        $queryText2 = $pdo->prepare($queryText2);
                        $queryText2->bindParam(':mail', $_COOKIE['loggedUser']);
                        $queryText2->execute();
                    } catch (PDOException $e) {
                        echo "Error de SQL<br>\n";
                        $e = $queryText2->errorInfo();
                        if ($e[0] != '00000') {
                            echo "\nPDO::errorInfo():\n";
                            die("Error accedint a dades: " . $e[2]);
                        }
                    }

                    if ($queryText2->rowCount() <= 0) {
                        echo "<p>No hay ninguna conversación,<br>descubra gente nueva y haga match</p>";
                    } else {
                        foreach ($queryText2 as $row) {
                            $queryUser = "SELECT * FROM users WHERE email_user = :mail;";

                            try {
                                $queryUser = $pdo->prepare($queryUser);
                                $queryUser->bindParam(':mail', $row['id_user']);
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
                                echo "<div class='messageUSer'>
                                        <img src='img/" . $rowUser['email_user'] . ".jpg'>
                                        <p>" . $rowUser['name'] . "</p>
                                    </div>";
                            }
                        }
                    }

                } else {
                    foreach ($queryText1 as $row) {
                        $queryUser = "SELECT * FROM users WHERE email_user = :mail;";

                        try {
                            $queryUser = $pdo->prepare($queryUser);
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
                                    <img src='img/" . $rowUser['email_user'] . ".jpg'>
                                    <p>" . $rowUser['name'] . "</p>
                                </div>";
                        }
                    }
                }

                ?>
            </div>
        </div>
    </main>

    <footer id="footer">
        <h3><a href="discober.php">Descobrir</a></h3>
        <h3><a href="messages.php">Missatges</a></h3>
        <h3><a href="profile.php">Perfil</a></h3>
    </footer>
</body>

</html>
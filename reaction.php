<?php
$match = false;
$matchUser = null;
//Comprueba se ha recibido reaction como parametro
if (isset($_POST["reaction"]) && isset($_POST["findUser"])) {
    $reaction = null;
    if ($_POST["reaction"] == "like") {
        $reaction = 2;
    }
    if ($_POST["reaction"] == "dislike") {
        $reaction = 1;
    }
    $mail = $_COOKIE['loggedUser'];
    $findUser = $_POST['findUser'];

    $status = 0;
    $logMessage = "";

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

    updateScore($mail, $pdo, 1);
    if ($reaction == 2) {
        updateScore($findUser, $pdo, 2);
    }

    //Comprobar si el usuario ha recibido una reacción antes
    $queryText = "SELECT * FROM interactions " .
        "WHERE id_user = :findUser AND id_receptor = :mail;";

    try {
        //preparem i executem la consulta
        $queryReaction = $pdo->prepare($queryText);
        $queryReaction->bindParam(':findUser', $findUser);
        $queryReaction->bindParam(':mail', $mail);
        $queryReaction->execute();
    } catch (PDOException $e) {
        echo "Error de SQL<br>\n";
        //comprovo errors:
        $e = $queryReaction->errorInfo();
        if ($e[0] != '00000') {
            echo "\nPDO::errorInfo():\n";
            die("Error accedint a dades: " . $e[2]);
        }
    }

    if ($queryReaction->rowCount() > 0) {
        $queryText = "UPDATE interactions " .
            "SET like_receptor = :reaction " .
            "SET date = CURRENT_TIMESTAMP() " .
            "WHERE id_user = :findUser AND id_receptor = :mail;";
    } else {
        $queryText = "INSERT INTO interactions " .
            "(id_user, id_receptor, like_user, date) " .
            "VALUES (:mail, :findUser, :reaction, CURRENT_TIMESTAMP());";
    }

    try {
        //preparem i executem la consulta
        $queryInsertReaction = $pdo->prepare($queryText);
        $queryInsertReaction->bindParam(':mail', $_COOKIE['loggedUser']);
        $queryInsertReaction->bindParam(':findUser', $findUser);
        $queryInsertReaction->bindParam(':reaction', $reaction);
        $queryInsertReaction->execute();
    } catch (PDOException $e) {
        echo "Error de SQL<br>\n";
        //comprovo errors:
        $e = $queryInsertReaction->errorInfo();
        if ($e[0] != '00000') {
            echo "\nPDO::errorInfo():\n";
            die("Error accedint a dades: " . $e[2]);
        }
    }

    if ($queryInsertReaction->rowCount() > 0) {
        if ($reaction == 2) {
            $logMessage = $_COOKIE['loggedUser'] . " ha enviado un Like a " . $findUser;
        } else {
            $logMessage = $_COOKIE['loggedUser'] . " ha enviado un Dislike a " . $findUser;
        }
        
        //Comprobar si el usuario ha recibido una reacción antes
        $queryText = "SELECT * FROM interactions " .
            "WHERE (id_user = :mail OR id_receptor = :mail) " .
            "AND (id_user = :findUser OR id_receptor = :findUser) " .
            "AND like_user = 2 AND like_receptor = 2;";

        try {
            //preparem i executem la consulta
            $queryMatches = $pdo->prepare($queryText);
            $queryMatches->bindParam(':findUser', $findUser);
            $queryMatches->bindParam(':mail', $mail);
            $queryMatches->execute();
        } catch (PDOException $e) {
            echo "Error de SQL<br>\n";
            //comprovo errors:
            $e = $queryReaction->errorInfo();
            if ($e[0] != '00000') {
                echo "\nPDO::errorInfo():\n";
                die("Error accedint a dades: " . $e[2]);
            }
        }

        if ($queryMatches->rowCount() > 0) {
            $match = true;
            $matchUser = $findUser;
            $logMessage = $mail . " y " . $findUser . " han hecho match";
        }

        echo json_encode([
            'status' => $status,
            'data' => $logMessage,
            'match' => $match,
            'matchUser' => $matchUser
        ]);
    }
}

function updateScore($mail, $pdo, $addedPoints)
{
    $queryText = "SELECT * FROM users " .
        "WHERE email_user = :mail;";

    try {
        //preparem i executem la consulta
        $queryScore = $pdo->prepare($queryText);
        $queryScore->bindParam(':mail', $mail);
        $queryScore->execute();
    } catch (PDOException $e) {
        echo "Error de SQL<br>\n";
        //comprovo errors:
        $e = $queryScore->errorInfo();
        if ($e[0] != '00000') {
            echo "\nPDO::errorInfo():\n";
            die("Error accedint a dades: " . $e[2]);
        }
    }

    if ($queryScore->rowCount() > 0 || $queryScore->rowCount() < 2) {
        foreach ($queryScore as $row) {
            $queryText = "UPDATE users SET points= :points WHERE email_user = :mail;";

            try {
                //preparem i executem la consulta
                $queryUpdateScore = $pdo->prepare($queryText);
                $queryUpdateScore->bindParam(':mail', $mail);
                $points = $row['points'] + $addedPoints;
                $queryUpdateScore->bindParam(':points', $points);
                $queryUpdateScore->execute();
            } catch (PDOException $e) {
                echo "Error de SQL<br>\n";
                //comprovo errors:
                $e = $queryUpdateScore->errorInfo();
                if ($e[0] != '00000') {
                    echo "\nPDO::errorInfo():\n";
                }
            }
        }
    }
}

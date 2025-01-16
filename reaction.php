<?php

//Comprueba se ha recibido reaction como parametro
if (isset($_POST["reaction"]) && isset($_POST["findUser"])) {
    $reactionText = $_POST["reaction"];
    $reaction = null;
    if ($reactionText = "like") {
        $reaction = 1;
    } else if ($reactionText = "dislike") {
        $reaction = 0;
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
            "WHERE id_user = :findUser AND id_receptor = :mail;";
    } else {
        $queryText = "INSERT INTO interactions " .
            "(id_user, id_receptor, like_user, date) " .
            "VALUES (:mail, :findUser, :reaction, CURRENT_DATE);";
    }

    try {
        //preparem i executem la consulta
        $queryInsertReaction = $pdo->prepare($queryText);
        $queryInsertReaction->bindParam(':mail', $_COOKIE['loggedUser']);
        $queryInsertReaction->bindParam(':findUser', $findUser);
        $queryInsertReaction->bindParam(':reaction', $reaction);
        $queryInsertReaction->execute();

        $status = 0;
        $logMessage = "Reacción guardada de " . $reactionText;
        echo json_encode([
            'status' => $status,
            'data' => $logMessage,
            'react' => $reactionText
        ]);
    } catch (PDOException $e) {
        echo "Error de SQL<br>\n";
        //comprovo errors:
        $e = $queryInsertReaction->errorInfo();
        if ($e[0] != '00000') {
            echo "\nPDO::errorInfo():\n";
            die("Error accedint a dades: " . $e[2]);
        }
    }
}

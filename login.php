<?php
if (isset($_POST['mail']) && isset($_POST['password'])) {
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $hashedPassword = hash('sha256', $password);

    $resp["status"]    = 0;
    $resp["msg"]   = '';

    try {
        $hostname = "localhost";
        $dbname = " ieti_tinder";
        $dbUsername = "ietitinder";
        $pw = "tinder123";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
    } catch (PDOException $e) {
        $resp["status"] = 1;
        $resp["msg"] = "Error al accedir a la base de dades" . $e->getMessage() . "\n";
        echo json_encode($resp);
        exit;
    }


    //preparem i executem la consulta
    $queryText = "SELECT * FROM users " .
        "WHERE email_user = :mail;";

    try {
        //preparem i executem la consulta
        $queryUser = $pdo->prepare($queryText);
        $queryUser->bindParam(':mail', $mail);
        $queryUser->execute();
    } catch (PDOException $e) {
        $resp["status"] = 1;
        //comprovo errors:
        $e = $queryUser->errorInfo();
        if ($e[0] != '00000') {
            $resp["msg"] = "Error de SQL - PDO::errorInfo(): " +
                "Error accedint a dades: " . $e[2];
            echo json_encode($resp);
            die("Error 1 accedint a dades: " . $e[2]);
        } else {
            $resp["msg"] = "Error de SQL";
            echo json_encode($resp);
        }
    }

    if ($queryUser->rowCount() <= 0 || $queryUser->rowCount() >= 2) {
        $resp["status"] = 1;
        $resp["msg"] = "Usuario incorrecto";
        echo json_encode($resp);
    } else {
        $queryText = "SELECT * FROM users " .
            "WHERE email_user = :mail AND password_user = :password;";

        try {
            //preparem i executem la consulta
            $queryUserAndPass = $pdo->prepare($queryText);
            $queryUserAndPass->bindParam(':mail', $mail);
            $queryUserAndPass->bindParam(':password', $hashedPassword);
            $queryUserAndPass->execute();
        } catch (PDOException $e) {
            $resp["status"] = 1;
            //comprovo errors:
            $e = $queryUserAndPass->errorInfo();
            if ($e[0] != '00000') {
                $resp["msg"] = "Error de SQL - PDO::errorInfo(): " +
                    "Error accedint a dades: " . $e[2];
                echo json_encode($resp);
                die("Error accedint a dades: " . $e[2]);
            } else {
                $resp["msg"] = "Error de SQL";
                echo json_encode($resp);
            }
        }

        if ($queryUserAndPass->rowCount() <= 0 || $queryUserAndPass->rowCount() >= 2) {
            $resp["status"] = 1;
            $resp["msg"] = "Contraseña incorrecta";
            echo json_encode($resp);
        } else {
            foreach ($queryUserAndPass as $row) {
                setcookie("loggedUser", $row['email_user'], time() + 1000 * 60 * 60 * 24 * 7);
                $resp["status"] = 0;
                $resp["msg"] = "Usuario logeado: " . $row['email_user'];
                echo json_encode($resp);
            }
        }
    }
}

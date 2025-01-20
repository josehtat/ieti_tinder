<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/script.js"></script>
    <title>Login - Affinity</title>
</head>


<body id="loginIndex">
    <script>
        <?php
        $status = 0;
        if (isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/discober.php";
            <?php } else {
            if (isset($_POST['mail']) && isset($_POST['password'])) {
                $mail = $_POST['mail'];
                $password = $_POST['password'];
                $hashedPassword = hash('sha256', $password);

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


                //preparem i executem la consulta
                $queryText = "SELECT * FROM users " .
                    "WHERE email_user = :mail;";

                try {
                    //preparem i executem la consulta
                    $queryUser = $pdo->prepare($queryText);
                    $queryUser->bindParam(':mail', $mail);
                    $queryUser->execute();
                } catch (PDOException $e) {
                    echo "Error de SQL<br>\n";
                    //comprovo errors:
                    $e = $queryUser->errorInfo();
                    if ($e[0] != '00000') {
                        echo "\nPDO::errorInfo():\n";
                        die("Error accedint a dades: " . $e[2]);
                    }
                }

                if ($queryUser->rowCount() <= 0 || $queryUser->rowCount() >= 2) {
                    $status = 1;
                    $logMessage = "Usuario incorrecto";
                } else {
                    $queryText = "SELECT * FROM users " .
                        "WHERE email_user = :mail AND password_user = :password AND account_status = 'active';";

                    try {
                        //preparem i executem la consulta
                        $queryUserAndPass = $pdo->prepare($queryText);
                        $queryUserAndPass->bindParam(':mail', $mail);
                        $queryUserAndPass->bindParam(':password', $hashedPassword);
                        $queryUserAndPass->execute();
                    } catch (PDOException $e) {
                        echo "Error de SQL<br>\n";
                        //comprovo errors:
                        $e = $queryUserAndPass->errorInfo();
                        if ($e[0] != '00000') {
                            echo "\nPDO::errorInfo():\n";
                            die("Error accedint a dades: " . $e[2]);
                        }
                    }

                    if ($queryUserAndPass->rowCount() <= 0 || $queryUserAndPass->rowCount() >= 2) {
                        $status = 2;
                        $logMessage = "Contraseña incorrecta";
                    } else {
                        foreach ($queryUserAndPass as $row) {
                            setcookie("loggedUser", $row['email_user'], time() + 1000 * 60 * 60 * 24 * 7);
                            $status = 0;
                            $logMessage = "Usuario logeado: " . $row['email_user'];
            ?>
                            window.location.href = "/discober.php";
        <?php
                        }
                    }
                }
            }
        }
        ?>
    </script>

    <div class="login-container">
            <h1>Affinity</h1>
            <h2>Un lugar para encontrar tu amor</h2>

            <div class="error-group">
                <?php
                if ($status > 0) { ?>
                    <p id="error-message"><?php echo $logMessage; ?></p>
                <?php } ?>
            </div>

        <form action="login.php" method="post" class="login-form" id="login">

            <div class="input-group">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="mail" placeholder="ejemplo@ieti.site" <?php if ($status == 1) echo 'class="inputError"' ?> required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="pass1234" <?php if ($status == 2) echo 'class="inputError"' ?> required>
            </div>
            
            <div class="submit-btn">
                <button type="submit">Iniciar sesión</button>
            </div>

            <div class="forgot-password">
                <p><a href="/forgot.php">¿Olvidaste la contraseña?</a></p>
            </div>

            <div class="register-link">
                <p><a href="/register.php">Crear una cuenta nueva</a></p>
            </div>
        </form>
    </div>
</body>

</html>
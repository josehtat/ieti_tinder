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
        function logMessage(errorCode, message) {
            var text = "";
            switch (errorCode) {
                case 0:
                    text = "[INFO - login.php] " + message;
                    break;
                case 1:
                case 2:
                case 3:
                case 4:
                    text = "[ERROR - login.php] " + message;
                    break;
            }
            var logParameters = {
                text: text
            };

            $.ajax({
                data: logParameters,
                url: 'logs.php',
                type: 'POST',
                success: logResult,
                dataType: 'json'
            });
        }

        function logResult(logRes) {
            console.log("logResult: ");
            console.log(logRes);
        }

    </script>

    <script>
        <?php
        $status = 0;
        $logMessage = "";

        if (isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/discober.php";
            <?php
        } else {
            if (isset($_POST['mail']) && isset($_POST['password'])) {
                $mail = $_POST['mail'];
                $password = $_POST['password'];
                $hashedPassword = hash('sha256', $password);

                try {
                    $hostname = "localhost";
                    $dbname = "ieti_tinder";
                    $dbUsername = "ietitinder";
                    $pw = "tinder123";
                    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
                } catch (PDOException $e) {
                    echo "Error al acceder a la base de datos - " . $e->getMessage();
                    exit;
                }

                // Consulta para obtener al usuario
                $queryText = "SELECT * FROM users WHERE email_user = :mail;";
                try {
                    $queryUser = $pdo->prepare($queryText);
                    $queryUser->bindParam(':mail', $mail);
                    $queryUser->execute();
                } catch (PDOException $e) {
                    echo "Error en la consulta de usuario: " . $e->getMessage();
                    exit;
                }

                if ($queryUser->rowCount() !== 1) {
                    $status = 1;
                    $logMessage = "Usuario no encontrado o datos incorrectos.";
                    ?>
                    logMessage(<?php echo $status ?>, '<?php echo $logMessage ?>');
                    <?php

                } else {
                    $user = $queryUser->fetch(PDO::FETCH_ASSOC);

                    if ($user['account_status'] === 'to verify') {
                        $status = 3;
                        $logMessage = "Cuenta pendiente de verificación. Por favor, verifica tu correo.";
                        ?>
                        logMessage(<?php echo $status ?>, '<?php echo $logMessage ?>');
                        <?php
                    } elseif ($user['account_status'] === 'inactive') {
                        $status = 4;
                        $logMessage = "Cuenta inactiva. Contacta al soporte.";
                        ?>
                        logMessage(<?php echo $status ?>, '<?php echo $logMessage ?>');
                        <?php
                    } elseif ($user['account_status'] === 'active') {
                        // Verificar contraseña
                        if ($user['password_user'] === $hashedPassword) {
                            setcookie("userRole", $user['role'], time() + 1000 * 60 * 60 * 24 * 7); // Guardar el rol del usuario
                            setcookie("loggedUser", $user['email_user'], time() + 1000 * 60 * 60 * 24 * 7);

                            if ($user['role'] === 'admin') { ?>
                                window.location.href = "/admin/index.php";
                            <?php } else { ?>
                                window.location.href = "/discober.php";
                            <?php }
                            $status = 0;
                            $logMessage = "Usuario logueado correctamente.";
                        } else {
                            $status = 2;
                            $logMessage = "Contraseña incorrecta.";
                            ?>
                            logMessage(<?php echo $status ?>, '<?php echo $logMessage ?>');
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
            <?php if ($status > 0) { ?>
                <p id="error-message"><?php echo $logMessage; ?></p>
            <?php } ?>
        </div>

        <form action="login.php" method="post" class="login-form" id="login">
            <div class="input-group">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="mail" placeholder="ejemplo@ieti.site" <?php if ($status == 1) echo 'class="inputError"'; ?> required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="pass1234" <?php if ($status == 2) echo 'class="inputError"'; ?> required>
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

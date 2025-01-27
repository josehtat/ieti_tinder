<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <title>Restablecer Contraseña - Affinity</title>
</head>

<body id="resetPasswordPage">
    <script>
        function logMessage(errorCode, message) {
            var text = "";
            switch (errorCode) {
                case 0:
                    text = "[INFO - reset_password.php] " + message;
                    break;
                case 1:
                case 2:
                case 3:
                case 4:
                    text = "[ERROR - reset_password.php] " + message;
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

        function showResetForm() {
            $('#sendLinkForm').hide();
            $('#resetPasswordForm').show();
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
            if (isset($_POST['mail'])) {
                $mail = $_POST['mail'];

                try {
                    $hostname = "localhost";
                    $dbname = "ieti_tinder";
                    $dbUsername = "ietitinder";
                    $pw = "tinder123";
                    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");

                    // Verificar si el correo electrónico existe en la base de datos
                    $queryText = "SELECT * FROM users WHERE email_user = :mail";
                    $queryUser = $pdo->prepare($queryText);
                    $queryUser->bindParam(':mail', $mail);
                    $queryUser->execute();

                    if ($queryUser->rowCount() === 1) {
                        $user = $queryUser->fetch(PDO::FETCH_ASSOC);

                        // Generar token único
                        $token = bin2hex(random_bytes(50));

                        // Insertar token en la base de datos
                        $insertToken = $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
                        $insertToken->execute([$mail, $token]);

                        // Incluir el autoload de Composer
                        require 'vendor/autoload.php';

                        use PHPMailer\PHPMailer\PHPMailer;
                        use PHPMailer\PHPMailer\SMTP;
                        use PHPMailer\PHPMailer\Exception;

                        $mail = new PHPMailer(true);

                        try {
                            // Configuración del servidor
                            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                            $mail->isSMTP();
                            $mail->Host       = 'smtp.gmail.com';
                            $mail->SMTPAuth   = true;
                            $mail->Username   = 'unaimunoz2024@gmail.com';
                            $mail->Password   = 'fdrh okqg yzpe wwen';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                            $mail->Port       = 465;

                            // Destinatarios
                            $mail->setFrom('unaimunoz2024@gmail.com', 'Unai');
                            $mail->addAddress($mail); // El correo del usuario

                            // Contenido del correo
                            $mail->isHTML(true);
                            $mail->Subject = 'Restablecer contraseña';
                            $mail->Body    = "Hola,<br><br>Haga clic en el siguiente enlace para restablecer su contraseña: <a href='http://tu_dominio.com/reset_password.php?token=$token'>Restablecer Contraseña</a><br><br>Si no solicitó este cambio, ignore este correo.";
                            $mail->AltBody = "Hola,\n\nHaga clic en el siguiente enlace para restablecer su contraseña: http://tu_dominio.com/reset_password.php?token=$token\n\nSi no solicitó este cambio, ignore este correo.";

                            $mail->send();
                            $status = 0;
                            $logMessage = "Correo de restablecimiento de contraseña enviado. Por favor, revise su bandeja de entrada.";
                            echo "<script>showResetForm();</script>"; // Mostrar el formulario de restablecimiento
                        } catch (Exception $e) {
                            $status = 4;
                            $logMessage = "El mensaje no se pudo enviar. Error de Mailer: {$mail->ErrorInfo}";
                        }
                    } else {
                        $status = 1;
                        $logMessage = "Correo electrónico no encontrado.";
                    }
                } catch (PDOException $e) {
                    $status = 4;
                    $logMessage = "Error al acceder a la base de datos: " . $e->getMessage();
                }
                ?>
                logMessage(<?php echo $status ?>, '<?php echo $logMessage ?>');
                <?php
            }

            // Manejar restablecimiento de contraseña
            if (isset($_POST['new_password']) && isset($_POST['token'])) {
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];
                $token = $_POST['token'];

                if ($new_password === $confirm_password) {
                    try {
                        $new_password_hashed = hash('sha256', $new_password);

                        // Obtener el correo electrónico correspondiente al token
                        $query = $pdo->prepare("SELECT email FROM password_resets WHERE token = ?");
                        $query->execute([$token]);

                        if ($query->rowCount() === 1) {
                            $email = $query->fetchColumn();

                            // Actualizar la contraseña del usuario
                            $updatePassword = $pdo->prepare("UPDATE users SET password_user = ? WHERE email_user = ?");
                            $updatePassword->execute([$new_password_hashed, $email]);

                            // Eliminar el token de restablecimiento
                            $deleteToken = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
                            $deleteToken->execute([$token]);

                            $status = 0;
                            $logMessage = "¡Contraseña actualizada correctamente! Ahora puede iniciar sesión.";
                        } else {
                            $status = 1;
                            $logMessage = "Enlace de restablecimiento inválido o expirado.";
                        }
                    } catch (PDOException $e) {
                        $status = 4;
                        $logMessage = "Error al acceder a la base de datos: " . $e->getMessage();
                    }
                } else {
                    $status = 2;
                    $logMessage = "Las contraseñas no coinciden.";
                }
                ?>
                logMessage(<?php echo $status ?>, '<?php echo $logMessage ?>');
                <?php
            }
        }
        ?>
    </script>

    <div class="forgotPassword-container">
        <h1>Affinity</h1>
        <h2>Introduce el correo electrónico con el que quiere restablecer tu contraseña</h2>

        <div class="error-group">
            <?php if ($status > 0) { ?>
                <p id="error-message"><?php echo $logMessage;?></p>
            <?php } ?>
        </div>

        <form id="sendLinkForm" action="forgot_password.php" method="post" class="login-form">
            <div class="input-group">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="mail" placeholder="" <?php if ($status == 1) echo 'class="inputError"'; ?> required>
            </div>
            <div class="submit-btn">
                <button type="submit">Enviar</button>
            </div>
        </form>

        <form id="resetPasswordForm" action="forgot_password.php" method="post" class="login-form" style="display:none;">
            <div class="input-group">
                <label for="new_password">Nueva Contraseña</label>
                <input type="password" id="new_password" name="new_password" placeholder="" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="" required>
            </div>
            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>" required>
            <div class="submit-btn">
                <button type="submit">Restablecer Contraseña</button>
            </div>
        </form>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="en" id="register-html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Affinity</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
</head>
<body class="register-body">
    <?php
    require 'vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $status = 0;
    $logMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = hash('sha256', $_POST['password']);
        $name = $_POST['name'];
        $surnames = $_POST['surnames'];
        $alias = $_POST['alias'];
        $birthday = $_POST['birthday'];
        $location = $_POST['location']; 
        $sex = $_POST['sex'];
        $sexOrientation = $_POST['sexOrientation'];
        
        try {
            $hostname = "localhost";
            $dbname = "ieti_tinder";
            $dbUsername = "ietitinder";
            $pw = "tinder123";
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
            
            // Check if email already exists
            $checkEmail = $pdo->prepare("SELECT email_user, account_status FROM users WHERE email_user = ?");
            $checkEmail->execute([$email]);
            
            if ($checkEmail->rowCount() > 0) {

                foreach ($checkEmail as $row) {
                    if ($row['account_status'] == 'active') {
                        $status = 2;
                        $logMessage = "Cuenta ya existente. Por favor, inicia sesión";
                        break;
                    } else if ($row['account_status'] == 'inactive') {
                        $query = "UPDATE users SET password_user = ?, name = ?, surnames = ?, alias = ?, birthday = ?, location = ?, sex = ?, sex_orientation = ?, account_status = 'to verify' WHERE email_user = ?";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$password, $name, $surnames, $alias, $birthday, $location, $sex, $sexOrientation, $email]);
                        $status = 0;
                        $logMessage = "Cuenta acutualizada. Porfavor verifica tu correo.";
                        break;
                    } else if ($row['account_status'] == 'to verify') {
                        $status = 2;
                        $logMessage = "Cuenta pendiente de verificación. Por favor, verifica tu correo.";
                        break;
                    }
                }

            } else {

                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'unaimunoz2024@gmail.com';                     //SMTP username
                    $mail->Password   = 'fdrh okqg yzpe wwen';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('administration@tinder1.ieti.site', 'Affinity');
                    $mail->addAddress($email); // Usamos la variable $email como destinatario

                    // Función para encriptar el correo electrónico
                    function encryptEmail($email) {
                        $encryption_key = 'kappachungus';  // Clave secreta (debe ser mantenida segura)
                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));  // IV aleatorio para mayor seguridad
                        
                        // Encriptar el correo electrónico usando AES-256-CBC
                        $encrypted_email = openssl_encrypt($email, 'aes-256-cbc', $encryption_key, 0, $iv);
                        
                        // Codificar el IV y el correo encriptado en Base64 para que puedan ser fácilmente pasados en la URL
                        return base64_encode($encrypted_email . '::' . $iv);
                    }

                    $verificationLink = "http://tinder1.ieti.site/login.php?validate=" . urlencode(encryptEmail($email));

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Verificar Cuenta';
                    $mail->Body = 'Haz clic en el siguiente enlace para verificar tu cuenta: 
                        <br><br>
                        <a href="' . $verificationLink . '" style="background-color:rgb(158, 132, 230); color: black; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block;">
                            Verifica tu cuenta
                        </a>';

                    $mail->send();

                    // Only insert user if email was sent successfully
                    $query = "INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'to verify')";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$email, $password, $name, $surnames, $alias, $birthday, $location, $sex, $sexOrientation]);

                    header('Location: login.php');
                    
                } catch (Exception $e) {
                    $status = 3;
                    $logMessage = "Registro fallido no se pudo enviar el correo";
                }
            }
        } catch (PDOException $e) {
            $status = 4;
            $logMessage = "Database error: " . $e->getMessage();
        }
    }
    ?>

    <div class="register-container">
        <h1>Affinity</h1>
        <h2>Un lugar para encontrar tu amor</h2>

        <div class="error-group">
            <?php
            if ($status > 0) { ?>
                <p id="error-message"><?php echo $logMessage; ?></p>
            <?php } ?>
        </div>

        <form action="register.php" method="post" class="register-form" id="registerForm">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="" required>
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="" minlength="8" required>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="input-group">
                    <label for="surnames">Apellidos</label>
                    <input type="text" id="surnames" name="surnames" required>
                </div>
            </div>

            <div class="input-group">
                <label for="alias">Alias</label>
                <input type="text" id="alias" name="alias" required>
            </div>

            <div class="input-group">
                <label for="birthday">Fecha de nacimiento</label>
                <div class="input-icon">
                    <input type="date" id="birthday" name="birthday" required>
                </div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label for="latitude">Latitud</label>
                    <input type="text" id="latitude" name="latitude" required readonly>
                </div>

                <div class="input-group">
                    <label for="longitude">Longitud</label>
                    <input type="text" id="longitude" name="longitude" required readonly>
                </div>
            </div>

            <input type="hidden" id="location" name="location" required>

            <div id="map" style="height: 300px; width: 100%;"></div>

            <div class="input-row" style="margin-top: 10px;">
                <div class="input-group">
                    <label for="sex">Sexo</label>
                    <select id="sex" name="sex" required>
                        <option value="" disabled selected>Seleccionar una opción</option>
                        <option value="M">Hombre</option>
                        <option value="F">Mujer</option>
                        <option value="Other">No Binario</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="sexOrientation">Orientación Sexual</label>
                    <select id="sexOrientation" name="sexOrientation" required>
                        <option value="" disabled selected>Seleccionar una opción</option>
                        <option value="heterosexual">Heterosexual</option>
                        <option value="homosexual">Homosexual</option>
                        <option value="bisexual">Bisexual</option>
                    </select>
                </div>
            </div>

            <div class="submit-btn">
                <button type="submit">Crear Cuenta</button>
            </div>

            <div class="login-link">
                <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
            </div>
        </form>
    </div>
    <script src="/js/register.js"></script>
</body>
</html>
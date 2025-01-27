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
                        $logMessage = "Account already verified";
                        break;
                    } else if ($row['account_status'] == 'inactive') {
                        $query = "UPDATE users SET password_user = ?, name = ?, surnames = ?, alias = ?, birthday = ?, location = ?, sex = ?, sex_orientation = ?, account_status = 'to verify' WHERE email_user = ?";
                        break;
                    } else if ($row['account_status'] == 'to verify') {
                        $status = 2;
                        $logMessage = "Account to be verified";
                        break;
                    }
                }

            } else {
                // Create verification link with email
                $verificationLink = "http://tinder1.ieti.site/login.php?validate=" . urlencode($email);
                $to = $email;
                $subject = "Verify your Affinity account";
                $message = "Welcome to Affinity!\n\nPlease click the following link to verify your account:\n" . $verificationLink;
                $headers = "From: administration@tinder1.ieti.site" . "\r\n" .
                          "Reply-To: administration@tinder1.ieti.site" . "\r\n" .
                          "X-Mailer: PHP/" . phpversion();

                // Try to send email first
                if(mail($to, $subject, $message, $headers)) {
                    // Only insert user if email was sent successfully
                    $query = "INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'to verify')";

                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$email, $password, $name, $surnames, $alias, $birthday, $location, $sex, $sexOrientation]);
                    
                    $status = 0;
                    $logMessage = "Registration successful! Please check your email to verify your account.";
                } else {
                    $status = 3;
                    $logMessage = "Registration failed: Could not send verification email. Please try again later.";
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
                        <option value="Heterosexual">Heterosexual</option>
                        <option value="Homosexual">Homosexual</option>
                        <option value="Bisexual">Bisexual</option>
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
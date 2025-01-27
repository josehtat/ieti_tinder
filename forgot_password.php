<!DOCTYPE html>
<html lang="en" id="register-html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Affinity</title>
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body class="forgotPassword-body">
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
            $checkEmail = $pdo->prepare("SELECT email_user FROM users WHERE email_user = ?");
            $checkEmail->execute([$email]);

            if ($checkEmail->rowCount() > 0) {
                $status = 1;
                $logMessage = "Email already registered";
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
                if (mail($to, $subject, $message, $headers)) {
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
        <h2>Introduce tu correo electronico</h2>

        <div class="error-group">
            <?php
            if ($status > 0) { ?>
                <p id="error-message"><?php echo $logMessage;?></p>
            <?php } ?>
        </div>

        <form action="register.php" method="post" class="register-form" id="registerForm">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="" required>
            </div>

            <div class="submit-btn">
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>
    <script>

    </script>
</body>

</html>
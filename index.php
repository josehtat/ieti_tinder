<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <title>Inicio - Affinity</title>
</head>

<body>
    <script>
        <?php if (isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/discober.php";
        <?php } ?>
    </script>

    <div class="login-container">
        <form action="login.php" method="post" class="login-form" id="login">
            <h2>Affinity</h2>
            <h3>Un lugar para encontrar tu amor</h3>
            <div class="input-group">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="mail" placeholder="exemplo@ieti.site" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="pass1234" required>
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

    <script>
        <?php if (isset($_COOKIE['loggedUser'])) { ?>
            window.location.href = "/discober.php";
        <?php } ?>

        function login(mail, password) {
            var parametros = {
                "mail": mail,
                "password": password
            };

            $.ajax({
                data: parametros,
                url: 'login.php',
                type: 'POST',
                success: loginResult,
                dataType: 'json'
            });
        }

        function loginResult(logRes) {
            console.log(logRes);
            if (logRes.status == 0) {
                window.location.href = "/discober.php";
            }
        }

        $(document).ready(function() {
            $("#login").submit(function(event) {
                event.preventDefault();
                login($("#mail").val(), $("#password").val());
            });

        });
    </script>
</body>

</html>
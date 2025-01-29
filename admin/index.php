<?php
// Verificar si el usuario tiene la cookie de autenticación y el rol adecuado
if (!isset($_COOKIE['loggedUser']) || !isset($_COOKIE['userRole']) || $_COOKIE['userRole'] !== 'admin') {
    // Redirigir al login si no está autenticado como administrador
    http_response_code(403);
    header("Location: ../error/403.php");
    die("Error 403: Prohibido");
}

// Puedes añadir un mensaje o lógica adicional aquí, si lo necesitas
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/script.js"></script>
    <title>Panel Administrativo</title>
</head>

<body class="admin-body">
    <div class="admin-container">
        <header>
            <div class="header-content">
                <h1>Bienvenido al Panel Administrativo</h1>
                <p>Esta sección es solo para usuarios con rol de administrador.</p>
            </div>
            <div class="header-buttons">
                <button id="backButton">Cerrar sesión de administrador</button>
                <button id="usersButton">Ver usuarios</button>
                <button id="logsButton">Ver logs</button>
            </div>
        </header>
        <main>
            <p>Esta es la pantalla de inicio de la sección de administración.</p>
        </main>
    </div>

    <script>
        // Resto de tu JavaScript
        $(document).ready(function () {
            // Resto de tu JavaScript
            $("#backButton").click(function () {
                $.post("/clear-cookies.php", function () {
                    // Redirect after cookies are cleared
                    window.location.href = "/";
                });
            });

            $("#logsButton").click(function () {
                window.location.href = "/admin/logs.php";
            });

            $("#usersButton").click(function () {
                window.location.href = "/admin/users.php";
            });
        })
    </script>
</body>

</html>
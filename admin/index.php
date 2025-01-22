<?php
// Verificar si el usuario tiene la cookie de autenticación y el rol adecuado
if (!isset($_COOKIE['loggedUser']) || !isset($_COOKIE['userRole']) || $_COOKIE['userRole'] !== 'admin') {
    // Redirigir al login si no está autenticado como administrador
    header("Location: /error403.php");
    exit;
}

// Eliminar la cookie de loggedUser y userRole al entrar en el panel administrativo
setcookie("loggedUser", "", time() - 3600, "/"); // Eliminar cookie loggedUser
setcookie("userRole", "", time() - 3600, "/");  // Eliminar cookie userRole

// Puedes añadir un mensaje o lógica adicional aquí, si lo necesitas
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
</head>

<body>
    <h1>Bienvenido al Panel Administrativo</h1>
    <p>Esta sección es solo para usuarios con rol de administrador.</p>
</body>

</html>

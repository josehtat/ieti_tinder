<?php
// Establecer el código de respuesta HTTP a 403 (Prohibido)
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 403 - Prohibido</title>
</head>

<body>
    <h1>Error 403</h1>
    <p>Acceso denegado. No tienes permiso para acceder a esta página.</p>
    <p><a href="/login.php">Volver al inicio de sesión</a></p>
</body>

</html>

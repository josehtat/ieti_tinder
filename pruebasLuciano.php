<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Errores</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/script.js"></script>
</head>
<body>

    <div id="messages-container"></div>

    <button onclick="showMessage('info', 'Información: Todo está correcto.')">Mostrar Info</button>
    <button onclick="showMessage('error', 'Error: Algo salió mal.')">Mostrar Error</button>
    <button onclick="showMessage('warning', 'Advertencia: Revisa los detalles.')">Mostrar Advertencia</button>

</body>
</html>

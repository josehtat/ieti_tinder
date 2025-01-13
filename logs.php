<?php
// Función que comprueba si el archivo existe y lo crea o actualiza si es necesario
function crearArchivoConFecha($texto) {
    // Obtener la fecha actual en formato YYYY-MM-DD
    $fecha = date('Y-m-d');
    
    // Definir la ruta de la carpeta logs
    $carpetaLogs = 'logs/';

    // Definir el nombre del archivo con la fecha actual y la ruta completa
    $archivo = $carpetaLogs . $fecha . '.txt';

    // Siempre escribir el texto en el archivo (agregar al final)
    file_put_contents($archivo, $texto . PHP_EOL, FILE_APPEND);
    echo "Texto guardado en el archivo de la carpeta logs.";
}

// Verificar si se ha enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el texto enviado desde AJAX
    $texto = $_POST['texto'] ?? '';

    if (!empty($texto)) {
        // Llamar a la función con el texto
        crearArchivoConFecha($texto);
    } else {
        echo "No se ha recibido texto válido.";
    }
}
?>
